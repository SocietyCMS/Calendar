<?php

namespace Modules\Calendar\Repositories;


class PresetRepository extends EloquentCalendarRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return 'Modules\\Calendar\\Entities\\Preset';
    }
}
