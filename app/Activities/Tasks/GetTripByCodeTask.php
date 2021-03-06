<?php

namespace App\Activities\Tasks;

use App\Exceptions\ElementNotFoundException;
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
        $trip = $this->trip->firstWhere('code', $code);

        if (!$trip) {
            throw new ElementNotFoundException('Trip not found');
        }

        return $trip;
    }
}
