<?php

namespace App\Service;

use Cake\Core\Configure;
use Twilio\Rest\Client;

/**
 * Class TwilioSMSVerificationService
 * @package App\Service
 */
class TwilioSMSVerificationService
{
    private string $phoneNumber;
    private string $serviceSid;
    private Client $twilio;

    /**
     * TwilioSMSVerificationService constructor.
     * @param string $phoneNumber
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function __construct(string $phoneNumber)
    {
        $config = Configure::read('Twilio');
        $this->phoneNumber = $phoneNumber;
        $sid = $config['sid'];
        $token = $config['token'];
        $this->serviceSid = $config['serviceSid'];
        $this->twilio = new Client($sid, $token);
    }

    /**
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function sendVerificationToken()
    {
        $this
            ->twilio
            ->verify
            ->v2
            ->services($this->serviceSid)
            ->verifications
            ->create($this->phoneNumber, 'sms');
    }

    /**
     * @param string $token
     * @return bool
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function isValidToken(string $token): bool
    {
        $verificationResult =
            $this
                ->twilio
                ->verify
                ->v2
                ->services($this->serviceSid)
                ->verificationChecks
                ->create($token,
                    ['to' => $this->phoneNumber]
                );
        return $verificationResult->status === 'approved';
    }
}
