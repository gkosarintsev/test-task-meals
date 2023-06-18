<?php

namespace Meals\Application\Feature\PollResult\UseCase\EmployeeSavesPollResult;

use Meals\Application\Component\DTO\PollResultDTO;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\TimeRangeToPollValidator;
use Meals\Application\Component\Validator\UserCanParticipationInPollsValidator;

class Interactor
{
    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var PollProviderInterface */
    private $pollProvider;

    /** @var DishProviderInterface */
    private $dishProvider;

    /** @var UserCanParticipationInPollsValidator */
    private $userCanParticipationInPallsValidator;

    /** @var PollIsActiveValidator */
    private $pollIsActiveValidator;

    /** @var TimeRangeToPollValidator */
    private $timeRangeToPollValidator;

    /** @var PollResultProviderInterface */
    private $pollResultProvider;

    /**
     * Interactor constructor.
     * @param EmployeeProviderInterface $employeeProvider
     * @param PollProviderInterface $pollProvider
     * @param DishProviderInterface $dishProvider
     * @param UserCanParticipationInPollsValidator $userCanParticipationInPallsValidator
     * @param PollIsActiveValidator $pollIsActiveValidator
     * @param TimeRangeToPollValidator $timeRangeToPollValidator
     * @param PollResultProviderInterface $pollResultProvider
     */
    public function __construct(
        EmployeeProviderInterface            $employeeProvider,
        PollProviderInterface                $pollProvider,
        DishProviderInterface                $dishProvider,
        UserCanParticipationInPollsValidator $userCanParticipationInPallsValidator,
        PollIsActiveValidator                $pollIsActiveValidator,
        TimeRangeToPollValidator             $timeRangeToPollValidator,
        PollResultProviderInterface          $pollResultProvider
    ) {
        $this->employeeProvider = $employeeProvider;
        $this->pollProvider = $pollProvider;
        $this->dishProvider = $dishProvider;
        $this->userCanParticipationInPallsValidator = $userCanParticipationInPallsValidator;
        $this->pollIsActiveValidator = $pollIsActiveValidator;
        $this->timeRangeToPollValidator = $timeRangeToPollValidator;
        $this->pollResultProvider = $pollResultProvider;
    }

    /**
     * @param int $employeeId
     * @param int $pollId
     * @param int $dishId
     * @param int $currentTime
     */
    public function savePoll(int $employeeId, int $pollId, int $dishId, int $currentTime): void
    {
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $poll = $this->pollProvider->getPoll($pollId);
        $dish = $this->dishProvider->getDish($dishId);

        $this->timeRangeToPollValidator->validate($currentTime);
        $this->userCanParticipationInPallsValidator->validate($employee->getUser());
        $this->pollIsActiveValidator->validate($poll);

        $pollResultDTO = new PollResultDTO($poll, $employee, $dish);
        $this->pollResultProvider->savePollResult($pollResultDTO);
    }
}
