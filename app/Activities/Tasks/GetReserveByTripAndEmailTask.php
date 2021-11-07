<?php

namespace App\Activities\Tasks;

use App\Models\Reserve;
use Illuminate\Support\Collection;

class GetReserveByTripAndEmailTask extends TaskAbstract
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
     * @return Collection
     */
    public function run(int $tripId, string $email): Collection
    {
        return $this->reserve->where([
            'trip_id' => $tripId,
            'email' => $email
        ])->get();
    }
}
