<?php declare(strict_types = 1);

namespace App\Tests\TestCase;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Trait ConstraintViolationListTrait
 */
trait ConstraintViolationListTrait
{
    /**
     * @return ObjectProphecy
     */
    public function getConstraintViolationListWithErrors(): ObjectProphecy
    {
        $constraintViolationList = $this->prophesize(ConstraintViolationList::class);
        $constraintViolation     = $this->prophesize(ConstraintViolation::class);

        $constraintViolation->getPropertyPath()->willReturn('field');
        $constraintViolation->getMessage()->willReturn('Error message');
        $constraintViolation->getMessageTemplate()->willReturn('GetMessageTemplate');

        $constraintViolationList->count()->willReturn(1);
        $constraintViolationList->getIterator()->willReturn(new \ArrayIterator([
            $constraintViolation->reveal(),
        ]));

        return $constraintViolationList;
    }

    /**
     * @return ObjectProphecy
     */
    public function getConstraintViolationListWithoutErrors(): ObjectProphecy
    {
        $constraintViolationList = $this->prophesize(ConstraintViolationList::class);
        $constraintViolationList->count()->willReturn(0);
        $constraintViolationList->getIterator()->willReturn(new \ArrayIterator());
        $constraintViolationList->addAll(Argument::type(ConstraintViolationList::class))->willReturn
        ($constraintViolationList);

        return $constraintViolationList;
    }
}
