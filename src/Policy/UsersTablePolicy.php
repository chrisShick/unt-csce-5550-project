<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\UsersTable;
use Authorization\IdentityInterface;

/**
 * Users policy
 */
class UsersTablePolicy
{
    /**
     * @param \Authorization\IdentityInterface $user user
     * @param \App\Model\Table\UsersTable $table cleared contracts table
     * @return bool
     */
    public function canIndex(IdentityInterface $user, UsersTable $table): bool
    {
        return $user->getOriginalData()->user_role->title === 'Admin';
    }
}
