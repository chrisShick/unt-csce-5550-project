<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Registration;
use Authorization\IdentityInterface;

/**
 * Registration policy
 */
class RegistrationPolicy
{
    /**
     * Check if $user can add Registration
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Registration $registration
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Registration $registration)
    {
    }

    /**
     * Check if $user can edit Registration
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Registration $registration
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Registration $registration)
    {
    }

    /**
     * Check if $user can delete Registration
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Registration $registration
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Registration $registration)
    {
    }

    /**
     * Check if $user can view Registration
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Registration $registration
     * @return bool
     */
    public function canView(IdentityInterface $user, Registration $registration)
    {
    }
}
