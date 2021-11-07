<?php

namespace App\Activities\Tasks;

use App\Models\Trip;
use Illuminate\Pagination\LengthAwarePaginator;

class ListTripsTask extends TaskAbstract
{
    /**
     * @param Trip $trip
     */
    public function __construct(
        private Trip $trip
    ) {}

    /**
     * @return LengthAwarePaginator
     */
    public function run(): LengthAwarePaginator
    {
        return $this->trip->with(['from', 'to'])->paginate();
    }
}
