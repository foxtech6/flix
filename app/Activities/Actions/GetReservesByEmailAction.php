<?php

namespace App\Activities\Actions;

use App\Activities\Tasks\GetReserveByTripAndEmailTask;
use Illuminate\Support\Collection;

class GetReservesByEmailAction extends ActionAbstract
{
    /**
     * @param GetReserveByTripAndEmailTask $getReserveByTripAndEmailTask
     */
    public function __construct(
        private GetReserveByTripAndEmailTask $getReserveByTripAndEmailTask,
    ) {}

    /**
     * @param string $email
     * @return Collection
     */
    public function run(string $email): Collection
    {
       return $this->getReserveByTripAndEmailTask->run(email: $email);
    }
}
