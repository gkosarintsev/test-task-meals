<?php


namespace Meals\Application\Component\DTO;

use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;

class PollResultDTO
{
    /** @var Poll */
    private $poll;

    /** @var Employee */
    private $employee;

    /** @var Dish */
    private $dish;

    /**
     * PollResult constructor.
     * @param Poll $poll
     * @param Employee $employee
     * @param Dish $dish
     */
    public function __construct(Poll $poll, Employee $employee, Dish $dish)
    {
        $this->poll = $poll;
        $this->employee = $employee;
        $this->dish = $dish;
    }

    /**
     * @return Poll
     */
    public function getPoll(): Poll
    {
        return $this->poll;
    }

    /**
     * @return Employee
     */
    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    /**
     * @return Dish
     */
    public function getDish(): Dish
    {
        return $this->dish;
    }
}
