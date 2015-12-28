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
     * @param EventRequest $request
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $input = array_merge($request->data, [
            'end' => (is_null($request->data['end']) && $request->data['allDay'] == true) ? Carbon::parse($request->data['start'])->addDay() : $request->data['end']
        ]);

        $event = $this->event->update($input, $id);
        return $this->response->item($event, new EventTransformer);
    }


    /**
     * @param EventRequest $request
     * @return mixed
     */
    public function store(EventStoreRequest $request)
    {
        $event = $this->event->create($request->input());

        return $this->successCreated();
    }
}
