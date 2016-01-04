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
                    Presets
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


    <div class="ui small modal ui form" id="eventDetail">
        <i class="close icon"></i>
        <div class="header">
            <div class="fields">

                <div class="twelve wide field">
                    <input type="text" name="first-name" v-model="event.title" placeholder="Title">
                </div>

                <div class="four wide field">
                    <div class="ui fluid floating dropdown button">
                        <span class="text"></span>
                        <div class="menu">
                            <div class="scrolling menu">
                                <div class="item">
                                    <div class="ui red empty circular label"></div>
                                    Important
                                </div>
                                <div class="item">
                                    <div class="ui blue empty circular label"></div>
                                    Announcement
                                </div>
                                <div class="item">
                                    <div class="ui black empty circular label"></div>
                                    Cannot Fix
                                </div>
                                <div class="item">
                                    <div class="ui purple empty circular label"></div>
                                    News
                                </div>
                                <div class="item">
                                    <div class="ui orange empty circular label"></div>
                                    Enhancement
                                </div>
                                <div class="item">
                                    <div class="ui empty circular label"></div>
                                    Change Declined
                                </div>
                                <div class="item">
                                    <div class="ui yellow empty circular label"></div>
                                    Off Topic
                                </div>
                                <div class="item">
                                    <div class="ui pink empty circular label"></div>
                                    Interesting
                                </div>
                                <div class="item">
                                    <div class="ui green empty circular label"></div>
                                    Discussion
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content">

            <div class="field">
                <label>Location</label>
                <input type="text" name="title" v-model="event.location">
            </div>

            <div class="field">
                <label>Description</label>
                <textarea rows="2" name="description" v-model="event.description"></textarea>
            </div>

            <div class="ui divider"></div>

            <div class="field">
                <div class="ui checked checkbox">
                    <input type="checkbox" checked="">
                    <label>All-day</label>
                </div>
            </div>


            <div class="field">
                <label>From</label>
                <input type="text" name="start" v-model="event.start">
            </div>

            <div class="field">
                <label>To</label>
                <input type="text" name="to" v-model="event.end">
            </div>

        </div>
        <div class="actions">
            <div class="ui black deny button">
                Cancle
            </div>
            <div class="ui positive right button">
                Update
            </div>
        </div>
    </div>


@endsection

@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="{{\Pingpong\Modules\Facades\Module::asset('calendar:css/Calendar.css')}}">
@endsection

@section('javascript')
    <script src="{{\Pingpong\Modules\Facades\Module::asset('calendar:js/vendor.js')}}"></script>
    <script>
        $('.ui.dropdown').dropdown();

        $('.ui.checkbox').checkbox();

        $('.ui.accordion').accordion({exclusive: false});

        function ini_eventPresets(preset) {
            preset.each(function () {

                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).data('title')),
                    location: $.trim($(this).data('location')),
                    description: $.trim($(this).data('description')),
                    allDay: Number($.trim($(this).data('allday'))),
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
                        event = response.data.data;
                        $('#calendar').fullCalendar('renderEvent', event);
                    });

                },
                eventClick: function (event, jsEvent) {

                    EventDetail.event = event;

                    $('.ui.modal')
                            .modal({
                                transition:'fade'
                            })
                            .modal('show')
                    ;

                    return;
                    if (!$(this).popup('exists')) {
                        $(this).popup({
                            popup: $('#eventDetail'),
                            target: $(this),
                            on: 'manual'
                        })
                    }


                    $(this).popup('show');
                    $(this).popup('reposition');

                }
            });

        });

    </script>


@endsection
