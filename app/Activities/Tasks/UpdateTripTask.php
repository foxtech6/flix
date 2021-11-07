<?php

namespace App\Activities\Tasks;

use App\Exceptions\ElementNotFoundException;
use App\Models\Trip;

class UpdateTripTask extends TaskAbstract
{
    /**
     * @param Trip $trip
     */
    public function __construct(
        private Trip $trip,
    ) {}

    /**
     * @param int $id
     * @param string|null $code
     * @param string|null $places
     * @param string|null $freePlaces
     * @param int|null $fromCityId
     * @param int|null $toCityId
     * @return Trip
     * @throws ElementNotFoundException
     */
    public function run(
        int $id,
        string $code = null,
        string $places = null,
        string $freePlaces = null,
        int $fromCityId = null,
        int $toCityId = null,
    ): Trip {
        $trip = $this->trip->find($id);

        if (!$trip) {
            throw new ElementNotFoundException('Trip not found');
        }

        if (null !== $code) {
            $trip->code = $code;
        }

        if (null !== $places) {
            $trip->$places = $places;
        }

        if (null !== $freePlaces) {
            $trip->free_places = $freePlaces;
        }

        if (null !== $fromCityId) {
            $trip->from_city_id = $fromCityId;
        }

        if (null !== $toCityId) {
            $trip->to_city_id = $toCityId;
        }

        $trip->save();

        return $trip;
    }
}
