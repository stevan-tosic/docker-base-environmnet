<?php declare(strict_types = 1);

namespace App\Core\Domain\Repository\User;

use App\Core\Domain\Entity\User\User;
use App\Core\Domain\Repository\BaseRepositoryInterface;

/**
 * Interface UserRepositoryInterface
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function save(User $user);

    /**
     * Get all users with role ROLE_SUPER_ADMIN.
     *
     * @return mixed
     */
    public function getPlatformAdmins();

    /**
     * @return mixed
     */
    public function getRepository();

    /**
     * @return mixed|void
     */
    public function findAll();
    /**
     * @param int $id
     *
     * @return User|null|object
     */
    public function findById($id);

    /**
     * @param array $criteria
     *
     * @return User|null|object
     */
    public function findOneBy(array $criteria);
}
