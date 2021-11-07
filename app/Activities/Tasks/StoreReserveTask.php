<?php

namespace App\Activities\Tasks;

use App\Models\Reserve;

class StoreReserveTask extends TaskAbstract
{
    /**
     * @param Reserve $reserve
     */
    public function __construct(
        private Reserve $reserve,
    ) {}

    /**
     * @param int $tripId
     * @param string $email
     * @param int $places
     */
    public function run(int $tripId, string $email, int $places): void
    {
        $this->reserve->create([
            'trip_id' => $tripId,
            'email' => $email,
            'places' => $places,
        ]);
    }
}
