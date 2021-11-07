<?php

namespace App\Http\Controllers;

use App\Activities\Actions\PlaceReservationAction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReserveController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(Request $request, PlaceReservationAction $action)
    {
        $this->validate($request, [
            'trip_code' => 'required|string|exists:trips,code',
            'email' => 'required|string|email',
            'places' => 'required|int|min:1',
        ]);

        $action->run($request->trip_code, $request->email, $request->places);

        return response()->json(['message' => 'You have successfully reserved places']);
    }
}
