<?php

namespace App\Core\Infrastructure\Persistence\Doctrine\Repository;

use App\Core\Domain\Entity\User\User;
use App\Core\Domain\Repository\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserRepository
 */
class UserRepository implements UserRepositoryInterface
{
    private $repository;
    private $entityManager;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository    = $entityManager->getRepository(User::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed|void
     */
    public function findAll()
    {
        $this->repository->findAll();
    }

    /**
     * @param int $id
     *
     * @return User|null|object
     */
    public function findById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $criteria
     *
     * @return User|null|object
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * @param User $user
     *
     * @return mixed|void
     */
    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Get all users with role ROLE_SUPER_ADMIN.
     *
     * @return mixed
     */
    public function getPlatformAdmins()
    {
        return $this->repository->createQueryBuilder('u')
            ->where('u.roles LIKE :role_super_admin')
            ->orWhere('u.roles LIKE :role_admin')
            ->setParameters(['role_super_admin' => '%ROLE_SUPER_ADMIN%', 'role_admin' => '%ROLE_ADMIN%'])
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all users with role ROLE_PROGRAMME_ADMIN.
     *
     * @return mixed
     */
    public function getProgramAdmins()
    {
        return $this->repository->createQueryBuilder('u')
            ->select('u.id')
            ->addSelect('u.uuid')
            ->addSelect('CONCAT(u.firstName, \' \', u.lastName) AS fullName')
            ->addSelect('u.jobTitle')
            ->addSelect('u.modified')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_PROGRAMME_ADMIN%')
            ->andWhere('u.enabled = true')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all users with role ROLE_RELATIONSHIP_MANAGER.
     *
     * @return mixed
     */
    public function getRelationshipAdmins()
    {
        return $this->repository->createQueryBuilder('u')
            ->select('u.id')
            ->addSelect('u.uuid')
            ->addSelect('CONCAT(u.firstName, \' \', u.lastName) AS fullName')
            ->addSelect('u.jobTitle')
            ->addSelect('u.modified')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_RELATIONSHIP_MANAGER%')
            ->andWhere('u.enabled = true')
            ->getQuery()
            ->getResult();
    }
}
