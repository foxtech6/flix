<?php

namespace App\Activities\Actions;

use App\Activities\Tasks\{GetReserveByTripAndEmailTask, GetTripByCodeTask, StoreReserveTask, UpdateTripTask};
use App\Exceptions\{PlacesNotAvailableException, ReserveExistsException, TripNotFoundException};
use Illuminate\Support\Facades\DB;

class PlaceReservationAction extends ActionAbstract
{
    /**
     * @param StoreReserveTask $storeReserveTask
     * @param GetTripByCodeTask $getTripByCodeTask
     * @param UpdateTripTask $updateTripTask
     * @param GetReserveByTripAndEmailTask $getReserveByTripAndEmailTask
     */
    public function __construct(
        private StoreReserveTask $storeReserveTask,
        private GetTripByCodeTask $getTripByCodeTask,
        private UpdateTripTask $updateTripTask,
        private GetReserveByTripAndEmailTask $getReserveByTripAndEmailTask,
    ) {}

    /**
     * @param string $tripCode
     * @param string $email
     * @param int $places
     */
    public function run(string $tripCode, string $email, int $places): void
    {
        DB::beginTransaction();
        try {
            $trip = $this->getTripByCodeTask->run($tripCode);

            if ($this->getReserveByTripAndEmailTask->run($trip->id, $email)->count() > 0) {
                throw new ReserveExistsException();
            }

            if (!$trip) {
                throw new TripNotFoundException();
            }

            $freePlaces = $trip->free_places - $places;

            if ($freePlaces < 0) {
                throw new PlacesNotAvailableException();
            }

            $this->storeReserveTask->run($trip->id, $email, $places);
            $this->updateTripTask->run($trip->id, freePlaces: $freePlaces);

            DB::commit();
        } catch (TripNotFoundException|PlacesNotAvailableException|ReserveExistsException $e) {
            DB::rollBack();

            throw $e;
        }
    }
}