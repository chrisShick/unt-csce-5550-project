<?php
declare(strict_types=1);

namespace App\Model\Behavior\Strategy;

use Cake\Core\Exception\Exception;

/**
 * Class AsymmetricStrategy
 *
 * @package App\Model\Behavior\Strategy
 */
class AsymmetricStrategy implements StrategyInterface
{
    /**
     * Public key.
     *
     * @var resource
     */
    private $__publicKey;

    /**
     * Private key.
     *
     * @var resource
     */
    private $__privateKey;

    /**
     * AsymmetricStrategy constructor.
     *
     * @param string $public Valid public certificate, can be:
     *   - an X.509 certificate resource
     *   - a PEM formatted public key
     *   - a string having the format `file://path/to/file.pem`. The named file
     *   must contain a PEM encoded certificate/public key (it may contain both).
     * @param string $private Optional. A valid private certificate, can be:
     *   - a string having the format file://path/to/file.pem. The named file
     *   must contain a PEM encoded certificate.
     *   - a PEM encoded certificate
     * @param string $passphrase Optional pass phrase.
     * @throws \Cake\Core\Exception\Exception
     */
    public function __construct($public, $private = null, $passphrase = null)
    {
        $this->__publicKey = openssl_get_publickey($public);
        if (!$this->__publicKey) {
            throw new Exception('Invalid public certificate: ' . $public);
        }

        $this->__privateKey = openssl_get_privatekey($private, $passphrase);
        if ($private !== null && !$this->__privateKey) {
            throw new Exception('Invalid private certificate: ' . $private);
        }
    }

    /**
     * @inheritDoc
     */
    public function encrypt($plain): string
    {
        return $this->publicEncrypt($plain, $this->__publicKey);
    }

    /**
     * @inheritDoc
     */
    public function decrypt($cipher): string
    {
        if (!$this->__privateKey) {
            return $cipher;
        }

        try {
            return $this->privateDecrypt($cipher, $this->__privateKey);
        } catch (\Exception $e) {
            return $cipher;
        }
    }

    /**
     * @param string $plain String to encrypt.
     * @param string $certificate Public key certificate, one of:
     *   - an X.509 certificate resource
     *   - a PEM formatted public key
     *   - a string having the format `file://path/to/file.pem`. The named file
     *   must contain a PEM encoded certificate/public key (it may contain both).
     * @return string
     */
    private function publicEncrypt($plain, $certificate): string
    {
        openssl_public_encrypt($plain, $cipher, openssl_get_publickey($certificate), OPENSSL_PKCS1_OAEP_PADDING);

        return base64_encode($cipher);
    }

    /**
     * @param string $cipher Cipher to decrypt.
     * @param string $certificate Private key certificate, one of:
     *   - a string having the format file://path/to/file.pem. The named file
     *   must contain a PEM encoded certificate.
     *   - a PEM encoded certificate
     * @param string $passphrase Private key's associated passphrase, if any.
     * @return string
     */
    private function privateDecrypt($cipher, $certificate, $passphrase = null): string
    {
        $resource = openssl_get_privatekey($certificate, $passphrase);
        openssl_private_decrypt(base64_decode($cipher), $plain, $resource, OPENSSL_PKCS1_OAEP_PADDING);

        return $plain;
    }
}
