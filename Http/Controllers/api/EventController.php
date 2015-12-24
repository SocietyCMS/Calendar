<?php

namespace Modules\Calendar\Http\Controllers\api;

use Illuminate\Http\Request;
use Modules\Calendar\Http\Requests\EventRequest;
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
    public function store(EventRequest $request)
    {
        $event = $this->event->create($request->input());

        return $this->successCreated();
    }
}
