<?php
declare(strict_types=1);

namespace App\Model\Behavior\Strategy;

use Cake\Utility\Security;

class DefaultStrategy implements StrategyInterface
{
    /**
     * Key used by CakePHP's security utility.
     *
     * @var string
     */
    private $__key;

    /**
     * DefaultStrategy constructor.
     *
     * @param string $key Used by the security utility.
     */
    public function __construct($key)
    {
        $this->__key = $key;
    }

    /**
     * @inheritDoc
     */
    public function encrypt($plain)
    {
        return Security::encrypt($plain, $this->__key);
    }

    /**
     * @inheritDoc
     */
    public function decrypt($cipher)
    {
        return Security::decrypt($cipher, $this->__key);
    }
}
