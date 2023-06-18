<?php

namespace Meals\Application\Component\Provider;

use Meals\Application\Component\DTO\PollResultDTO;

interface PollResultProviderInterface
{
    public function savePollResult(PollResultDTO $pollResult);
}
