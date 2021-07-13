<?php
declare(strict_types=1);

namespace App\Authenticator;

use App\Service\TwilioSMSVerificationService;
use ArrayAccess;
use Authentication\Authenticator\FormAuthenticator as CakeFormAuthenticator;
use Authentication\Authenticator\ResultInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\UrlChecker\UrlCheckerTrait;
use Cake\Utility\Hash;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Two Factor Form Authenticator
 *
 * Authenticates an identity based on the POST data of the request.
 */
class TwoFactorFormAuthenticator extends CakeFormAuthenticator
{
    use UrlCheckerTrait;

    /**
     * @var TwilioSMSVerificationService
     */
    protected $_twilioService;

    /**
     * Default config for this object.
     * - `fields` The fields to use to identify a user by.
     * - `loginUrl` Login URL or an array of URLs.
     * - `urlChecker` Url checker config.
     * - `userSessionKey` Session key to store user after 1ss factor auth
     * - `phoneProperty` User model property containing user's phone number
     * - `codeField` Request field containing one-time code
     *
     * @var array
     */
    protected $_defaultConfig = [
        'loginUrl' => null,
        'userSessionKey' => 'TwoFactorAuth.user',
        'urlChecker' => 'Authentication.Default',
        'fields' => [
            IdentifierInterface::CREDENTIAL_USERNAME => 'username',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ],
        'codeField' => 'code',
        'isEnabled2faProperty' => 'is_verified',
    ];

    /**
     * Authenticates the identity contained in a request. Will use the `config.userModel`, and `config.fields`
     * to find POST data that is used to find a matching record in the `config.userModel`. Will return false if
     * there is no post data, either username or password is missing, or if the scope conditions have not been met.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request that contains login information.
     * @return \Authentication\Authenticator\ResultInterface
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        if (!$this->_checkUrl($request)) {
            return $this->_buildLoginUrlErrorResult($request);
        }

        $code = Hash::get($request->getParsedBody(), $this->getConfig('codeField'));
        if (!is_null($code)) {
            return $this->authenticateCode($request, $code);
        } else {
            return $this->authenticateCredentials($request);
        }
    }

    /**
     * 2nd factor authentication
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @param string $code One-time code
     * @return \Authentication\Authenticator\ResultInterface
     */
    protected function authenticateCode(ServerRequestInterface $request, $code): ResultInterface
    {
        $user = $this->_getSessionUser($request);

        if (!$user) {
            // User hasn't passed 1st factor auth
            return new Result(null, Result::FAILURE_CREDENTIALS_MISSING);
        }

        $phone = $user->getPhoneNumber();
        if (!$this->_verifyCode($phone, $code)) {
            // 2nd factor auth code is invalid
            return new Result(null, Result::TWO_FACTOR_AUTH_FAILED);
        }

        $this->_unsetSessionUser($request);

        return new Result($user, Result::SUCCESS);
    }

    /**
     * 1st factor authentication
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return \Authentication\Authenticator\ResultInterface
     */
    protected function authenticateCredentials(ServerRequestInterface $request): ResultInterface
    {
        $result = parent::authenticate($request);

        if (!$result->isValid()) {
            // The user is invalid or the 2FA secret is not enabled/present
            return $result;
        }

        $user = $result->getData();

        // Store user authenticated with 1 factor
        $this->_setSessionUser($request, $user);

        $this->getTwilioService($user->getPhoneNumber())->sendVerificationToken();

        return new Result(null, Result::TWO_FACTOR_AUTH_REQUIRED);
    }

    /**
     * Verify 2FA code
     * @param string $phone phone number
     * @param string $code One-time code
     * @return bool
     */
    protected function _verifyCode($phone, $code): bool
    {
        try {
            return $this->getTwilioService($phone)->isValidToken($code);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get pre-authenticated user from the session
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return array|null
     */
    protected function _getSessionUser(ServerRequestInterface $request)
    {
        /** @var \Cake\Http\Session $session */
        $session = $request->getAttribute('session');

        return $session->read($this->getConfig('userSessionKey'));
    }

    /**
     * Store pre-authenticated user in the session
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @param \ArrayAccess $user User
     */
    protected function _setSessionUser(ServerRequestInterface $request, ArrayAccess $user)
    {
        /** @var \Cake\Http\Session $session */
        $session = $request->getAttribute('session');
        $session->write($this->getConfig('userSessionKey'), $user);
    }

    /**
     * Clear pre-authenticated user from the session
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     */
    protected function _unsetSessionUser(ServerRequestInterface $request)
    {
        /** @var \Cake\Http\Session $session */
        $session = $request->getAttribute('session');

        $session->delete($this->getConfig('userSessionKey'));
    }

    public function getTwilioService($phone)
    {
        if (!$this->_twilioService) {
            $this->_twilioService = new TwilioSMSVerificationService($phone);
        }

        return $this->_twilioService;
    }
}
