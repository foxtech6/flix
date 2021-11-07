<?php

namespace App\Http\Controllers\Admin;

use App\Activities\Actions\GetReservesByTripAndEmailAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReserveResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;

class ReserveController extends Controller
{
    /**
     * @param string $id
     * @param GetReservesByTripAndEmailAction $action
     * @return MessageBag|AnonymousResourceCollection
     */
    public function index(string $id, GetReservesByTripAndEmailAction $action): MessageBag|AnonymousResourceCollection
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|int|exists:trips,id',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return ReserveResource::collection($action->run(tripId: $id));
    }
}
