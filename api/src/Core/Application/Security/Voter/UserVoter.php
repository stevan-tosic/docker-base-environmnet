<?php declare(strict_types = 1);

namespace App\Core\Application\Security\Voter;

use App\Core\Domain\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class UserVoter
 */
class UserVoter extends Voter
{
    const CREATE = 'CREATE';
    const EDIT = 'EDIT';
    const DISABLE = 'DISABLE';
    const SHOW = 'SHOW';

    /** @var AuthorizationCheckerInterface $authChecker */
    private $authChecker;

    /**
     * PartnerVoter constructor.
     *
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    public function supports($attribute, $subject): bool
    {
        if (!\in_array($attribute, [
            self::CREATE,
            self::EDIT,
            self::DISABLE,
            self::SHOW,
        ])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isAutoGranted(): bool
    {
        return $this->authChecker->isGranted(User::ROLE_ADMIN);
    }

    /**
     * @param string         $attribute
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if ($this->isAutoGranted()) {
            return true;
        }

        return false;
    }
}
