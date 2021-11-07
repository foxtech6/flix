<?php

namespace App\Activities\Tasks;

use App\Models\Trip;

class GetTripByCodeTask extends TaskAbstract
{
    /**
     * @param Trip $trip
     */
    public function __construct(
        private Trip $trip,
    ) {}

    /**
     * @param string $code
     * @return Trip
     */
    public function run(string $code): Trip
    {
        return $this->trip->firstWhere('code', $code);
    }
}
