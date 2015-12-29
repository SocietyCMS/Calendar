<?php

namespace Modules\Calendar\Http\Controllers\backend;

use Modules\Core\Http\Controllers\AdminBaseController;

class CalendarController extends AdminBaseController
{
    public function index()
    {
        $presets = $this->apiDispatcher->get('api/calendar/preset');
        return view('calendar::backend.index', compact('presets'));
    }
}
