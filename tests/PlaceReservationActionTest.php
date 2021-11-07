<?php

use App\Exceptions\{
    ElementNotFoundException,
    IncorrectPlacesCountException,
    PlacesNotAvailableException,
    ReserveExistsException
};
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Activities\Tasks\{StoreReserveTask, GetTripByCodeTask, UpdateTripTask, GetReserveByTripAndEmailTask};
use App\Models\{City, Reserve, Trip};
use App\Activities\Actions\PlaceReservationAction;

class PlaceReservationActionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var PlaceReservationAction
     */
    private PlaceReservationAction $action;

    /**
     * @var Trip
     */
    private Trip $trip;

    public function setUp(): void
    {
        parent::setUp();
        $this->runDatabaseMigrations();
        $this->artisan('db:seed');

        $this->trip = Trip::create([
            'code' => 'E51Hxl',
            'from_city_id' => City::firstWhere(['name' => 'Berlin', 'country' => 'Germany'])->id,
            'to_city_id' => City::firstWhere(['name' => 'Paris', 'country' => 'France'])->id,
            'places' => 20,
            'free_places' => 20,
        ]);

        $this->action = new PlaceReservationAction(
            new StoreReserveTask(new Reserve()),
            new GetTripByCodeTask(new Trip()),
            new UpdateTripTask(new Trip()),
            new GetReserveByTripAndEmailTask(new Reserve()),
        );
    }

    /**
     * @throws Exception
     */
    public function testRun(): void
    {
        $addPlaces = rand(1, $this->trip->free_places);
        $email = 'test@email.com';

        $this->action->run($this->trip->code, $email, $addPlaces);

        self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places + $addPlaces);
        self::assertEquals(
            $addPlaces,
            Reserve::firstWhere(['trip_id' => $this->trip->id, 'email' => $email])->places
        );
    }

    /**
     * @throws Exception
     */
    public function testRunWithReserveExistsException(): void
    {
        $addPlaces = rand(1, $this->trip->free_places);
        $email = 'test@email.com';

        try {
            self::expectException(ReserveExistsException::class);

            $this->action->run($this->trip->code, $email, $addPlaces);
            $this->action->run($this->trip->code, $email, $addPlaces);
        } finally {
            self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places + $addPlaces);
            self::assertEquals(
                $addPlaces,
                Reserve::firstWhere(['trip_id' => $this->trip->id, 'email' => $email])->places
            );
        }
    }

    /**
     * @throws Exception
     */
    public function testRunWithPlacesNotAvailableException(): void
    {
        $email = 'test@email.com';

        try {
            self::expectException(PlacesNotAvailableException::class);

            $this->action->run($this->trip->code, $email, rand($this->trip->free_places, 10000));
        } finally {
            self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places);
            self::assertNull(Reserve::firstWhere(['trip_id' => $this->trip->id, 'email' => $email]));
        }
    }

    /**
     * @throws Exception
     */
    public function testRunWithIncorrectPlacesCountException(): void
    {
        $email = 'test@email.com';

        try {
            self::expectException(IncorrectPlacesCountException::class);

            $this->action->run($this->trip->code, $email, -2);
        } finally {
            self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places);
            self::assertNull(Reserve::firstWhere(['trip_id' => $this->trip->id, 'email' => $email]));
        }
    }

    /**
     * @throws Exception
     */
    public function testRunWithElementNotFoundException(): void
    {
        $email = 'test@email.com';

        try {
            self::expectException(ElementNotFoundException::class);

            $this->action->run('testCode', $email, 2);
        } finally {
            self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places);
            self::assertNull(Reserve::firstWhere(['trip_id' => $this->trip->id, 'email' => $email]));
        }
    }
}
