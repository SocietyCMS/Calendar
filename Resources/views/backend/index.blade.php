@extends('layouts.master')

@section('title')
    {!! config('calendar.name') !!}
@endsection
@section('subTitle')
    Hello World
@endsection

@section('content')

    <div class="ui grid">
        <div class="four wide column">

            <div class="ui styled accordion">
                <div class="title">
                    <i class="dropdown icon"></i>
                    Saved Events
                </div>
                <div class="content" id="external-events">
                    <div class="transition hidden">
                        @foreach($presets as $preset)
                            <div class="ui {{$preset->className}} calendar event"
                                 data-title="{{$preset->title}}"
                                 data-location="{{$preset->location}}"
                                 data-description="{{$preset->description}}"
                                 data-allday="{{$preset->allDay}}"
                                 data-start="{{$preset->start->toIso8601String()}}"
                                 data-end="{{$preset->end->toIso8601String()}}"
                                 data-duration="{{ $preset->start->diffInMinutes($preset->end) }}"
                                 data-classname="ui {{$preset->className}} calendar event"
                            >{{$preset->title}}</div>
                        @endforeach
                    </div>
                </div>

                <div class="title">
                    <i class="dropdown icon"></i>
                    Create Event
                </div>
                <div class="content">
                    <div class="transition hidden">

                        <div class="btn-group">
                            <i class="red big square icon"></i>
                            <i class="orange big square icon"></i>
                            <i class="yellow big square icon"></i>
                            <i class="olive big square icon"></i>
                            <i class="green big square icon"></i>
                            <i class="teal big square icon"></i>
                            <i class="blue big square icon"></i>
                            <i class="violet big square icon"></i>
                            <i class="purple big square icon"></i>
                            <i class="pink big square icon"></i>
                            <i class="brown big square icon"></i>
                            <i class="grey big square icon"></i>
                        </div>

                        <div class="ui action input">
                            <input type="text" placeholder="Title">
                            <button class="ui button">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="one wide column">
        </div>
        <div class="ten wide column">
            <div class="ui blue tall stacked segment">
                <div id='calendar'></div>
            </div>
        </div>
    </div>



    <div class="ui popup" id="eventDetail">
        <div class="header">@{{ event.title }}</div>
        <div class="content">@{{ event.location }}</div>
        <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum
            tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas
            semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
    </div>

@endsection

@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="{{\Pingpong\Modules\Facades\Module::asset('calendar:css/Calendar.css')}}">
@endsection

@section('javascript')
    <script src="{{\Pingpong\Modules\Facades\Module::asset('calendar:js/vendor.js')}}"></script>
    <script>
        $('.ui.accordion').accordion({exclusive: false});

        function ini_eventPresets(preset) {
            preset.each(function () {

                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).data('title')),
                    location: $.trim($(this).data('location')),
                    description: $.trim($(this).data('description')),
                    allDay: $.trim($(this).data('allday')),
                    start: $.trim($(this).data('start')),
                    end: $.trim($(this).data('end')),
                    duration: $.trim($(this).data('duration')),
                    className: $.trim($(this).data('classname'))
                };


                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 1070,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });
        }

        ini_eventPresets($('#external-events .calendar.event'));

        var EventDetail = new Vue({
            el: '#eventDetail',
            data: {
                event: {
                    title: '',
                    location: '',
                    description: '',
                    allDay: false,
                    start: null,
                    end: null
                }
            }
        });

        $(document).ready(function () {

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week: 'week',
                    day: 'day'
                },
                firstDay: 1,
                editable: true,
                droppable: true,
                events: function (start, end, timezone, callback) {
                    var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.index')}}');
                    resource.get({start: start.format(), end: end.format()}).then(function (response) {
                        callback(response.data.data)
                    });

                },
                eventResize: function (event, delta, revertFunc) {
                    var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.update', ['event' => ':id'])}}');

                    resource.update({id: event.id}, event).then(function (response) {
                        $('#calendar').fullCalendar('updateEvent', event);
                    }, function (errorResponse) {
                        revertFunc();
                    });
                },
                eventDrop: function (event, delta, revertFunc) {
                    var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.update', ['event' => ':id'])}}');

                    resource.update({id: event.id}, event).then(function (response) {
                        $('#calendar').fullCalendar('updateEvent', event);
                    }, function (errorResponse) {
                        revertFunc();
                    });
                },
                eventRender: function (event, element) {

                },
                drop: function (date, jsEvent, ui) {

                    var originalEventObject = $(this).data('eventObject');
                    var event = $.extend({}, originalEventObject);

                    event.start = date;
                    if (event.end > 0) {
                        event.end = date.clone().add({minutes: event.duration});
                    }

                    var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.store')}}');
                    resource.save(event).then(function (response) {
                        event.id = response.id;
                        $('#calendar').fullCalendar('updateEvent', event);
                    });

                    console.log(event);
                    $('#calendar').fullCalendar('renderEvent', event);
                },
                eventClick: function (event, jsEvent) {

                    if (!$(this).popup('exists')) {
                        $(this).popup({
                            popup: $('#eventDetail'),
                            target: $(this),
                            on: 'manual'
                        })
                    }

                    EventDetail.event = event;
                    $(this).popup('show');
                    $(this).popup('reposition');

                }
            });

        });

    </script>


@endsection
