<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use Exception;
use App\Activities\Actions\{
    DeleteReserveAction,
    GetReservesByTripAndEmailAction,
    PlaceReservationAction,
    UpdateReservePlacesAction
};
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
     * @throws Exception
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
     * @param GetReservesByTripAndEmailAction $action
     * @return MessageBag|AnonymousResourceCollection
     */
    public function index(string $email, GetReservesByTripAndEmailAction $action): MessageBag|AnonymousResourceCollection
    {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|string|email|exists:reserves,email',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return ReserveResource::collection($action->run(email: $email));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param UpdateReservePlacesAction $action
     * @return JsonResponse|MessageBag
     * @throws Exception
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

    /**
     * @param int $id
     * @param DeleteReserveAction $action
     * @return JsonResponse|MessageBag
     */
    public function delete(int $id, DeleteReserveAction $action): JsonResponse|MessageBag
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|int|exists:reserves,id',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $action->run($id);

        return response()->json(['message' => 'Your reservation has been successfully canceled']);
    }
}
