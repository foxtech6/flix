<?php

namespace App\Activities\Actions;

use App\Activities\Tasks\GetReserveByTripAndEmailTask;
use Illuminate\Support\Collection;

class GetReservesByTripAndEmailAction extends ActionAbstract
{
    /**
     * @param GetReserveByTripAndEmailTask $getReserveByTripAndEmailTask
     */
    public function __construct(
        private GetReserveByTripAndEmailTask $getReserveByTripAndEmailTask,
    ) {}

    /**
     * @param int|null $tripId
     * @param string|null $email
     * @return Collection
     */
    public function run(int $tripId = null, string $email = null): Collection
    {
        if (null !== $email && null !== $tripId) {
            return $this->getReserveByTripAndEmailTask->run($tripId, $email);
        }

        if (null !== $tripId) {
            return $this->getReserveByTripAndEmailTask->run(tripId: $tripId);
        }

        return $this->getReserveByTripAndEmailTask->run(email: $email);
    }
}
