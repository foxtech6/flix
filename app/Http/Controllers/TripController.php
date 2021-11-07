<?php

namespace App\Http\Controllers;

use App\Activities\Actions\ListTripsAction;
use App\Http\Resources\TripResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TripController extends Controller
{
    /**
     * @param ListTripsAction $action
     * @return AnonymousResourceCollection
     */
    public function index(ListTripsAction $action): AnonymousResourceCollection
    {
        return TripResource::collection($action->run());
    }
}
