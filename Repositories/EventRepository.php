<?php

namespace Modules\Calendar\Repositories;


class EventRepository extends EloquentCalendarRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return 'Modules\\Calendar\\Entities\\Event';
    }
}
