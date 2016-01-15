<?php

$router->group(['prefix' => '/calendar'], function ($router) {
	// Calendar
	$router->group(['middleware' => ['permission:calendar::manage-calendar']], function () {
		get('calendar', ['as' => 'backend::calendar.calendar.index', 'uses' => 'CalendarController@index']);
	});

});