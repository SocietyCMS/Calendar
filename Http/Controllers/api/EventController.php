<?php

namespace Modules\Calendar\Http\Controllers\api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Calendar\Http\Requests\EventStoreRequest;
use Modules\Calendar\Http\Requests\EventUpdateRequest;
use Modules\Calendar\Repositories\EventRepository;
use Modules\Calendar\Transformers\EventTransformer;
use Modules\Core\Http\Controllers\ApiBaseController;

/**
 * Class EventController
 * @package Modules\Calendar\Http\Controllers\api
 */
class EventController extends ApiBaseController
{

    /**
     * @var EventRepository
     */
    private $event;

    /**
     * EventController constructor.
     * @param EventRepository $event
     */
    public function __construct(EventRepository $event)
    {
        parent::__construct();
        $this->event = $event;
    }

    /**
     * @return array
     */
    public function index(Request $request)
    {
        $events = $this->event->findWhereBetween([
            ['start', [$request->start, $request->end]],
            ['end', [$request->start, $request->end]],
        ]);

        return $this->response->collection($events, new EventTransformer);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(EventUpdateRequest $request, $id)
    {
        $classNames = array_flip(array_except(array_flip($request->className), ['ui', 'calendar', 'event']));

        $input = array_merge($request->input(), [
            'end' => (is_null($request->end) && $request->allDay == true) ? Carbon::parse($request->start)->addDay() : $request->end,
            'className' => implode(' ', $classNames)
        ]);

        $event = $this->event->update($input, $id);
        return $this->response->item($event, new EventTransformer);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function store(EventStoreRequest $request)
    {
        $event = $this->event->create($request->input());
        return $this->successCreated();
    }
}
