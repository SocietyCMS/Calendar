<?php

namespace Modules\Calendar\Http\Controllers\backend;

use Modules\Core\Http\Controllers\AdminBaseController;

class CalendarController extends AdminBaseController
{

    public function index()
    {
        return view('calendar::backend.index');
    }
}
