<?php

namespace App\Activities\Tasks;

use App\Exceptions\ElementNotFoundException;
use App\Models\Trip;

class GetTripTask extends TaskAbstract
{
    /**
     * @param Trip $trip
     */
    public function __construct(
        private Trip $trip,
    ) {}

    /**
     * @param int $id
     * @return Trip
     */
    public function run(int $id): Trip
    {
        $trip = $this->trip->find($id);

        if (!$trip) {
            throw new ElementNotFoundException('Trip not found');
        }

        return $trip;
    }
}
