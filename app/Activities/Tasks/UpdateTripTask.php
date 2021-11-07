<?php

namespace App\Activities\Tasks;

use App\Exceptions\TripNotFoundException;
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
     * @throws TripNotFoundException
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
            throw new TripNotFoundException();
        }

        if ($code !== null) {
            $trip->code = $code;
        }

        if ($places !== null) {
            $trip->$places = $places;
        }

        if ($freePlaces !== null) {
            $trip->free_places = $freePlaces;
        }

        if ($fromCityId !== null) {
            $trip->from_city_id = $fromCityId;
        }

        if ($toCityId !== null) {
            $trip->to_city_id = $toCityId;
        }

        $trip->save();

        return $trip;
    }
}
