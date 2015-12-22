<?php

$router->group(['prefix' => '/calendar'], function () {
	get('calendar', ['as' => 'backend::calendar.calendar.index', 'uses' => 'CalendarController@index']);
});