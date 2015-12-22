<?php

$router->get('calendar/{uri}', ['uses' => 'PublicController@uri', 'as' => 'calendar']);
