<?php

namespace Modules\Calendar\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\Activity\RecordsActivity;
use Carbon\Carbon;


/**
 * Class Preset
 * @package Modules\Calendar\Entities
 */
class Preset extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calendar__presets';

    /**
     * The fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = ['title', 'location', 'description', 'className','allDay', 'start', 'end'];


    /**
     * @var array
     */
    protected $dates = ['start', 'end', 'deleted_at'];


    /**
     * @param  string $value
     * @return string
     */
    public function setStartAttribute($value)
    {
        $this->attributes['start'] = Carbon::parse($value);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function setEndAttribute($value)
    {
        if (!is_null($value)) {
            $this->attributes['end'] = Carbon::parse($value);
        } else {
            $this->attributes['end']=null;
        }
    }

}
