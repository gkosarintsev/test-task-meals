<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\NotInTimeRangeToPollException;
use Meals\Application\Component\Validator\TimeRangeToPollValidator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TimeRangeToPollValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $validator = new TimeRangeToPollValidator();
        verify($validator->validate(strtotime('2023-06-19 12:00:00')))->null(); // понедельник
    }

    public function testFailWeekDay()
    {
        $this->expectException(NotInTimeRangeToPollException::class);

        $validator = new TimeRangeToPollValidator();
        verify($validator->validate(strtotime('2023-06-20 12:00:00'))); // вторник
    }

    public function testFailEarly()
    {
        $this->expectException(NotInTimeRangeToPollException::class);

        $validator = new TimeRangeToPollValidator();
        verify($validator->validate(strtotime('2023-06-19 05:59:00')));
    }

    public function testFailLate()
    {
        $this->expectException(NotInTimeRangeToPollException::class);

        $validator = new TimeRangeToPollValidator();
        verify($validator->validate(strtotime('2023-06-19 22:01:00')));
    }

}
