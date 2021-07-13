<?php
namespace App\Model\Behavior\Strategy;

use ParagonIE\Halite\Alerts\InvalidKey;
use ParagonIE\Halite\HiddenString;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;
use ParagonIE\Halite\Symmetric\EncryptionKey;

/**
 * Class HaliteStrategy
 * @package App\Model\Behavior\Strategy
 */
class HaliteStrategy implements StrategyInterface
{
    /**
     * EncryptionKey
     *
     * @var EncryptionKey EncryptionKey
     */
    private EncryptionKey $encryptionKey;

    /**
     * HaliteStrategy constructor.
     * @param string $key get key
     * @throws \ParagonIE\Halite\Alerts\CannotPerformOperation
     * @throws \ParagonIE\Halite\Alerts\InvalidKey
     * @throws \SodiumException
     */
    public function __construct(string $key)
    {
        $key = KeyFactory::loadEncryptionKey(CONFIG . $key . '.key');

        if (!$key instanceof EncryptionKey) {
            throw new InvalidKey();
        }

        $this->encryptionKey = $key;
    }

    /**
     * Encryption method
     *
     * @param string $plain get
     * @return string
     * @throws \ParagonIE\Halite\Alerts\CannotPerformOperation
     * @throws \ParagonIE\Halite\Alerts\InvalidDigestLength
     * @throws \ParagonIE\Halite\Alerts\InvalidMessage
     * @throws \ParagonIE\Halite\Alerts\InvalidType
     */
    public function encrypt($plain)
    {
        $hiddenString = new HiddenString($plain);

        return Symmetric::encrypt($hiddenString, $this->encryptionKey);
    }

    /**
     * Decryption method
     *
     * @param string $cipher text
     * @return HiddenString|string
     * @throws \ParagonIE\Halite\Alerts\CannotPerformOperation
     * @throws \ParagonIE\Halite\Alerts\InvalidDigestLength
     * @throws \ParagonIE\Halite\Alerts\InvalidMessage
     * @throws \ParagonIE\Halite\Alerts\InvalidSignature
     * @throws \ParagonIE\Halite\Alerts\InvalidType
     */
    public function decrypt($cipher)
    {
        return Symmetric::decrypt($cipher, $this->encryptionKey)->getString();
    }
}
