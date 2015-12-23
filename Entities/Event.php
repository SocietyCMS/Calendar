<?php

namespace Modules\Calendar\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\Activity\RecordsActivity;



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
    protected $fillable = ['title', 'location', 'description', 'allDay', 'start', 'end'];

    /**
     * Views for the Dashboard timeline.
     *
     * @var string
     */
    protected static $templatePath = 'calendar::backend.activities';

    protected $dates = ['start', 'end', 'deleted_at'];
}
