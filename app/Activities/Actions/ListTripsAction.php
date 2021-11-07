<?php

namespace App\Activities\Actions;

use App\Activities\Tasks\ListTripsTask;
use Illuminate\Pagination\LengthAwarePaginator;

class ListTripsAction extends ActionAbstract
{
    /**
     * @param ListTripsTask $listTripsTask
     */
    public function __construct(
        private ListTripsTask $listTripsTask
    ) {}

    /**
     * @return LengthAwarePaginator
     */
    public function run(): LengthAwarePaginator
    {
        return $this->listTripsTask->run();
    }
}
