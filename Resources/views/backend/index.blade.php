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
                        <div class="ui red calendar event">Red</div>
                        <div class="ui orange calendar event">Orange</div>
                        <div class="ui yellow calendar event">Yellow</div>
                        <div class="ui olive calendar event">Olive</div>
                        <div class="ui green calendar event">Green</div>
                        <div class="ui teal calendar event">Teal</div>
                        <div class="ui blue calendar event">Blue</div>
                        <div class="ui violet calendar event">Violet</div>
                        <div class="ui purple calendar event">Purple</div>
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
        <div class="header">@{{ title }}</div>
        <div class="content">@{{ location }}</div>
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

        function ini_events(ele) {
            ele.each(function () {

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 1070,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });
        }

        ini_events($('#external-events .calendar.event'));

        var EventDetail = new Vue({
            el: '#eventDetail',
            data: {
                title: '',
                location: '',
                description: '',
                allDay: false,
                start: null,
                end: null
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
                events: function (start, end, timezone, callback) {
                    var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.index')}}');
                    resource.get({start: start.format(), end: end.format()}).then(function (response) {
                        callback(response.data.data)
                    });

                },
                eventResize: function(event, delta, revertFunc) {
                    var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.update', ['event' => ':id'])}}');

                    resource.update({id: event.id},{data: event}).then(function (response) {
                        $('#calendar').fullCalendar('updateEvent',event);
                    }, function (errorResponse) {
                        revertFunc();
                    });

                },
                eventDrop: function(event, delta, revertFunc) {
                    var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.update', ['event' => ':id'])}}');

                    resource.update({id: event.id},{data: event}).then(function (response) {
                        $('#calendar').fullCalendar('updateEvent',event);
                    }, function (errorResponse) {
                        revertFunc();
                    });
                },
                eventReceive: function(event){
                    var data = {
                        title: 'Doorm',
                        start: new Date()
                    };

                    var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.store')}}');

                    resource.save({data: data}).then(function (response) {
                        event.id = response.id;
                        $('#calendar').fullCalendar('updateEvent',event);
                    });
                },
                eventRender: function (event, element) {
                    $(element).popup({
                        on: 'click',
                        title    : event.title,
                        content  : event.location
                    });

                },

                drop: function (date, jsEvent) {

                    var copiedEventObject = {};
                    copiedEventObject.title = "Demo";
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = true;

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject);
                },

                editable: true,
                droppable: true
            });

        });

    </script>


@endsection
