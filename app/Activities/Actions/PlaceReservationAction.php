<?php

namespace App\Activities\Actions;

use Exception;
use App\Activities\Tasks\{GetReserveByTripAndEmailTask, GetTripByCodeTask, StoreReserveTask, UpdateTripTask};
use App\Exceptions\{PlacesNotAvailableException, ReserveExistsException};
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
     * @throws Exception
     */
    public function run(string $tripCode, string $email, int $places): void
    {
        DB::beginTransaction();
        try {
            $trip = $this->getTripByCodeTask->run($tripCode);

            if (0 < $this->getReserveByTripAndEmailTask->run($trip->id, $email)->count()) {
                throw new ReserveExistsException();
            }

            $freePlaces = $trip->free_places - $places;

            if (0 > $freePlaces) {
                throw new PlacesNotAvailableException();
            }

            $this->storeReserveTask->run($trip->id, $email, $places);
            $this->updateTripTask->run($trip->id, freePlaces: $freePlaces);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
