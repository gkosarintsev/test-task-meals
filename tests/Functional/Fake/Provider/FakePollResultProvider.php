<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\DTO\PollResultDTO;
use Meals\Application\Component\Provider\PollResultProviderInterface;

class FakePollResultProvider implements PollResultProviderInterface
{
    public function savePollResult(PollResultDTO $pollResult) {

    }
}
