<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\AbstractPasswordHasher;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property string $id
 * @property string $user_role_id
 * @property string $username
 * @property string $country_code
 * @property string $phone
 * @property string $password
 * @property string $name
 * @property bool $is_verified
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\UserRole $user_role
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_role_id' => true,
        'username' => true,
        'country_code' => true,
        'phone' => true,
        'password' => true,
        'name' => true,
        'is_verified' => true,
        'created' => true,
        'modified' => true,
        'user_role' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];

    /**
     * @param string $password
     * @return string|null
     */
    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return null;
    }

    /**
     * @param string $phone
     * @return string
     */
    protected function _setPhone(string $phone): string{
        if ($phone[0] === '0'){
            //remove the leading 0 from the phone number
            return substr($phone,1);
        }
        return $phone;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string {
        return "+{$this->country_code}{$this->phone}";
    }
}
