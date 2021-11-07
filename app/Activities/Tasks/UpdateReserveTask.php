<?php

namespace App\Activities\Tasks;

use App\Exceptions\ElementNotFoundException;
use App\Models\Reserve;

class UpdateReserveTask extends TaskAbstract
{
    /**
     * @param Reserve $reserve
     */
    public function __construct(
        private Reserve $reserve,
    ) {}

    /**
     * @param int $id
     * @param string|null $email
     * @param string|null $places
     * @return Reserve
     */
    public function run(
        int $id,
        string $email = null,
        string $places = null,
    ): Reserve {
        $reserve = $this->reserve->find($id);

        if (!$reserve) {
            throw new ElementNotFoundException('Reserve not found');
        }

        if (null != $places) {
            $reserve->places = $places;
        }

        if (null != $email) {
            $reserve->email = $email;
        }

        $reserve->save();

        return $reserve;
    }
}
