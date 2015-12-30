<?php

namespace Modules\Calendar\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\Activity\RecordsActivity;
use Carbon\Carbon;


/**
 * Class Event
 * @package Modules\Calendar\Entities
 */
class Event extends Model
{
    use RecordsActivity;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calendar__events';

    /**
     * The fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = ['title', 'location', 'description', 'className', 'allDay', 'start', 'end'];

    /**
     * Views for the Dashboard timeline.
     *
     * @var string
     */
    protected static $templatePath = 'calendar::backend.activities';

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
