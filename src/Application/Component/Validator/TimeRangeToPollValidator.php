<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\NotInTimeRangeToPollException;

class TimeRangeToPollValidator
{
    const START = '06:00:00';
    const END = '22:00:00';
    const WEEK_DAY = 'Mon';

    public function validate(int $timestamp): void
    {
        $currentTime = (new \DateTime())->setTimestamp($timestamp);
        $weekDay = $currentTime->format('D');
        $format = 'H:i:s';
        $currentTime = \DateTime::createFromFormat($format, $currentTime->format($format));
        $startTime = \DateTime::createFromFormat($format, self::START);
        $endTime   = \DateTime::createFromFormat($format, self::END);

        if ($weekDay != self::WEEK_DAY || $startTime > $currentTime || $currentTime > $endTime) {
            throw new NotInTimeRangeToPollException();
        }
    }
}
