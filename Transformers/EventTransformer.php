<?php

namespace Modules\Calendar\Transformers;

use League\Fractal;

class EventTransformer extends Fractal\TransformerAbstract
{
    public function transform($event)
    {
        return [
            'id' => (int)$event->id,
            'title' => $event->title,
            'location' => $event->location,
            'description' => $event->description,
            'allDay' => (bool)$event->allDay,
            'start' => $event->start->toIso8601String(),
            'end' => (is_null($event->end)) ? null : $event->end->toIso8601String(),
            'className' => 'ui calendar event '.$event->className
        ];
    }
}
