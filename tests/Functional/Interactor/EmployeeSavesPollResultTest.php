<?php

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\NotInTimeRangeToPollException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Feature\PollResult\UseCase\EmployeeSavesPollResult\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
//use tests\Meals\Functional\Fake\FakeTimeRangeToPollValidator;
use Meals\Application\Component\Validator\TimeRangeToPollValidator;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakePollResultProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeSavesPollResultTest extends FunctionalTestCase
{
    const TIME_IN_CORRECT_RANGE = '2023-06-19 12:00:00';
    const TIME_IN_NOT_CORRECT_RANGE = '2023-06-20 12:00:00';

    public function testSuccessful()
    {
        $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            self::TIME_IN_CORRECT_RANGE
        );
        $this->addToAssertionCount(1);
    }

    public function testUserHasNotPermissions()
    {
        $this->expectException(AccessDeniedException::class);

        $this->performTestMethod(
            $this->getEmployeeWithNoPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            self::TIME_IN_CORRECT_RANGE
        );
    }

    public function testPollIsNotActive()
    {
        $this->expectException(PollIsNotActiveException::class);

        $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(false),
            $this->getDish(),
            self::TIME_IN_CORRECT_RANGE
        );
    }

    public function testNotInTimeRange()
    {
        $this->expectException(NotInTimeRangeToPollException::class);

        $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(false),
            $this->getDish(),
            self::TIME_IN_NOT_CORRECT_RANGE
        );
    }

    private function performTestMethod(
        Employee $employee,
        Poll $poll,
        Dish $dish,
        $time
    ) {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);
        $this->getContainer()->get(FakePollResultProvider::class);

        $this->getContainer()
            ->get(Interactor::class)
            ->savePoll($employee->getId(), $poll->getId(), $dish->getId(), strtotime($time));
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::PARTICIPATION_IN_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getPoll(bool $active): Poll
    {
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                new DishList([]),
            )
        );
    }

    private function getDish(): Dish
    {
        return new Dish(
            1,
            'title',
            'description'
        );
    }
}
