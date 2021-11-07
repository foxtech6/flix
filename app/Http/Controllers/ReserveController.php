<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use App\Activities\Actions\{GetReservesByEmailAction, PlaceReservationAction, UpdateReservePlacesAction};
use App\Http\Resources\ReserveResource;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class ReserveController extends Controller
{
    /**
     * @param Request $request
     * @param PlaceReservationAction $action
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, PlaceReservationAction $action): JsonResponse
    {
        $this->validate($request, [
            'trip_code' => 'required|string|exists:trips,code',
            'email' => 'required|string|email',
            'places' => 'required|int|min:1',
        ]);

        $action->run($request->trip_code, $request->email, $request->places);

        return response()->json(['message' => 'You have successfully reserved places']);
    }

    /**
     * @param string $email
     * @param GetReservesByEmailAction $action
     * @return MessageBag|AnonymousResourceCollection
     */
    public function index(string $email, GetReservesByEmailAction $action): MessageBag|AnonymousResourceCollection
    {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|string|email|exists:reserves,email',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return ReserveResource::collection($action->run($email));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param UpdateReservePlacesAction $action
     * @return JsonResponse|MessageBag
     */
    public function updatePlaces(Request $request, int $id, UpdateReservePlacesAction $action): JsonResponse|MessageBag
    {
        $validator = Validator::make(array_merge(['id' => $id], $request->all()), [
            'id' => 'required|int|exists:reserves,id',
            'places' => 'required|int|min:2',
            'mode' => [
                'required',
                sprintf('in:%s,%s', Reserve::MODE_REMOVE_PLACES, Reserve::MODE_ADD_PLACES),
            ],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $action->run($request->id, $request->places, $request->mode);

        return response()->json(['message' => 'You have successfully updated places']);
    }
}
