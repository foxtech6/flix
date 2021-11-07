<?php

namespace App\Activities\Actions;


use App\Exceptions\IncorrectPlacesCountException;
use App\Exceptions\PlacesNotAvailableException;
use App\Activities\Tasks\{GetReserveTask, GetTripTask, UpdateReserveTask, UpdateTripTask};
use App\Models\Reserve;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateReservePlacesAction extends ActionAbstract
{
    /**
     * @param GetReserveTask $getReserveTask
     * @param GetTripTask $getTripTask
     * @param UpdateTripTask $updateTripTask
     * @param UpdateReserveTask $updateReserveTask
     */
    public function __construct(
        private GetReserveTask $getReserveTask,
        private GetTripTask $getTripTask,
        private UpdateTripTask $updateTripTask,
        private UpdateReserveTask $updateReserveTask,
    ) {}

    /**
     * @param int $id
     * @param int $places
     * @param string $mode
     * @throws Exception
     */
    public function run(int $id, int $places, string $mode): void
    {
        if (0 > $places) {
            throw new IncorrectPlacesCountException();
        }

        DB::beginTransaction();
        try {
            $reserve = $this->getReserveTask->run($id);
            $trip = $this->getTripTask->run($reserve->trip_id);

            if (Reserve::MODE_ADD_PLACES === $mode) {
                $freePlaces = $trip->free_places - $places ;
                $this->updateTripTask->run(
                    $trip->id,
                    freePlaces: 0 > $freePlaces ? throw new PlacesNotAvailableException() : $freePlaces,
                );

                $this->updateReserveTask->run($reserve->id, places: $reserve->places + $places);
            }

            if (Reserve::MODE_REMOVE_PLACES === $mode) {
                $removePlaces = $reserve->places - $places;
                $this->updateReserveTask->run(
                    $reserve->id,
                    places: 1 > $removePlaces ? throw new IncorrectPlacesCountException() : $removePlaces,
                );

                $this->updateTripTask->run($trip->id, freePlaces: $trip->free_places + $places);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
