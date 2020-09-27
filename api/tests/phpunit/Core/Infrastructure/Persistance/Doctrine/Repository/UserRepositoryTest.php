<?php

use App\Core\Domain\Entity\Programme\Programme;
use App\Core\Domain\Entity\User\User;
use App\Core\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Prophecy\Argument;
use App\Tests\TestCase\RepositoryTestCase;

/**
 * Class UserRepositoryTest
 */
class UserRepositoryTest extends RepositoryTestCase
{
    protected $entityClass = User::class;

    protected $repositoryClass = UserRepository::class;

    public function testConstructor()
    {
        static::assertInstanceOf($this->repositoryClass, new UserRepository($this->em->reveal()));
    }

    public function testFindByIdNullResult()
    {
        static::assertEquals(null, $this->newRepositoryInstance->findById(1));
    }

    public function testFindOneByNullResult()
    {
        static::assertEquals(null, $this->newRepositoryInstance->findOneBy(['id' => 1]));
    }

    public function testFindAll()
    {
        static::assertEquals(null, $this->newRepositoryInstance->findAll());
    }

    public function testFindByIdObjectResult()
    {
        $em = $this->em;
        $entityRepository = $this->prophesize(EntityRepository::class);
        $entityRepository->find(1)->willReturn($this->newObjectInstance);
        $em->getRepository($this->entityClass)->willReturn($entityRepository->reveal());
        $instance = new UserRepository($this->em->reveal());

        static::assertInstanceOf($this->entityClass, $instance->findById(1));
    }

    public function testSaveObject()
    {
        $instance = $this->newRepositoryInstance;
        $instance->save($this->newObjectInstance);
        static::assertInstanceOf($this->repositoryClass, $instance);
    }

    /**
     * @expectedException \TypeError
     */
    public function testSaveTypeError()
    {
        $this->newRepositoryInstance->save('');
    }
}
