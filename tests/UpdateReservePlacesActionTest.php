<?php

use App\Exceptions\{ElementNotFoundException, IncorrectPlacesCountException, PlacesNotAvailableException};
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Activities\Tasks\{GetReserveTask, GetTripTask, UpdateTripTask, UpdateReserveTask};
use App\Models\{City, Reserve, Trip};
use App\Activities\Actions\UpdateReservePlacesAction;

class UpdateReservePlacesActionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var UpdateReservePlacesAction
     */
    private UpdateReservePlacesAction $action;

    /**
     * @var Trip
     */
    private Trip $trip;

    /**
     * @var Reserve
     */
    private Reserve $reserve;

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
            'free_places' => 15,
        ]);

        $this->reserve = Reserve::create([
            'trip_id' => $this->trip->id,
            'email' => 'test@email.com',
            'places' => 5,
        ]);

        $this->action = new UpdateReservePlacesAction(
            new GetReserveTask(new Reserve()),
            new GetTripTask(new Trip()),
            new UpdateTripTask(new Trip()),
            new UpdateReserveTask(new Reserve()),
        );
    }

    /**
     * @throws Exception
     */
    public function testRunAddMode(): void
    {
        $places = 5;
        $this->action->run($this->reserve->id, $places, Reserve::MODE_ADD_PLACES);

        self::assertEquals($this->reserve->places + $places, Reserve::find($this->reserve->id)->places);
        self::assertEquals($this->trip->free_places - $places, Trip::find($this->trip->id)->free_places);
    }

    /**
     * @throws Exception
     */
    public function testRunRemoveMode(): void
    {
        $places = 4;

        $this->action->run($this->reserve->id, $places, Reserve::MODE_REMOVE_PLACES);

        self::assertEquals($this->reserve->places - $places, Reserve::find($this->reserve->id)->places);
        self::assertEquals($this->trip->free_places + $places, Trip::find($this->trip->id)->free_places);
    }

    /**
     * @throws Exception
     */
    public function testRunWithPlacesNotAvailableException(): void
    {
        try {
            self::expectException(PlacesNotAvailableException::class);

            $this->action->run($this->reserve->id, 20, Reserve::MODE_ADD_PLACES);
        } finally {
            self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places);
            self::assertEquals($this->reserve->places, Reserve::find($this->reserve->id)->places);
        }
    }

    /**
     * @throws Exception
     */
    public function testRunWithIncorrectPlacesCountException(): void
    {
        try {
            self::expectException(IncorrectPlacesCountException::class);

            $this->action->run($this->reserve->id, 20, Reserve::MODE_REMOVE_PLACES);
        } finally {
            self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places);
            self::assertEquals($this->reserve->places, Reserve::find($this->reserve->id)->places);
        }
    }

    /**
     * @throws Exception
     */
    public function testRunWithIncorrectPlacesCountExceptionInParameter(): void
    {
        try {
            self::expectException(IncorrectPlacesCountException::class);

            $this->action->run($this->reserve->id, -20, Reserve::MODE_REMOVE_PLACES);
        } finally {
            self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places);
            self::assertEquals($this->reserve->places, Reserve::find($this->reserve->id)->places);
        }
    }

    /**
     * @throws Exception
     */
    public function testRunWithElementNotFoundException(): void
    {
        try {
            self::expectException(ElementNotFoundException::class);

            $this->action->run(rand(1000, 10000), 2, Reserve::MODE_REMOVE_PLACES);
        } finally {
            self::assertEquals($this->trip->free_places, Trip::find($this->trip->id)->free_places);
            self::assertEquals($this->reserve->places, Reserve::find($this->reserve->id)->places);
        }
    }
}
