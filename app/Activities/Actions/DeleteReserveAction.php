<?php

namespace App\Activities\Actions;

use App\Activities\Tasks\DeleteReserveTask;
use App\Activities\Tasks\GetReserveTask;
use App\Activities\Tasks\GetTripTask;
use App\Activities\Tasks\UpdateTripTask;
use Exception;
use Illuminate\Support\Facades\DB;

class DeleteReserveAction extends ActionAbstract
{
    /**
     * @param DeleteReserveTask $deleteReserveTask
     * @param GetTripTask $getTripTask
     * @param UpdateTripTask $updateTripTask
     * @param GetReserveTask $getReserveTask
     */
    public function __construct(
        private DeleteReserveTask $deleteReserveTask,
        private GetTripTask $getTripTask,
        private UpdateTripTask $updateTripTask,
        private GetReserveTask $getReserveTask,
    ) {}

    /**
     * @param int $id
     */
    public function run(int $id): void
    {
        DB::beginTransaction();
        try {
            $reserve = $this->getReserveTask->run($id);
            $trip = $this->getTripTask->run($reserve->trip_id);

            $this->updateTripTask->run($trip->id, freePlaces: $trip->free_places + $reserve->places);
            $this->deleteReserveTask->run($id);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();
        }
    }
}
