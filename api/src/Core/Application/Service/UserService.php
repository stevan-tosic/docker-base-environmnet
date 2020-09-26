<?php declare( strict_types = 1);

namespace App\Core\Application\Service;

use App\Core\Domain\Entity\User\User;
use App\Core\Domain\Repository\User\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class UserService
 */
class UserService
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository      = $userRepository;
    }

    /**
     * @param User  $user
     * @param array $data
     *
     * @return array
     *
     * @throws \Exception
     */
    public function execute(User $user, array $data): array
    {
        if (empty($data)) {
            throw new BadRequestHttpException('Malformed request');
        }

        $userExist = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($userExist) {
            return ['errors' => ['email' => 'This email is already used']];
        }

        $this->userRepository->save($user);

        $result = [];

        return ['data' => $result];
    }
}
