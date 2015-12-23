@extends('layouts.master')

@section('title')
	{!! config('calendar.name') !!}
@endsection
@section('subTitle')
	Hello World
@endsection

@section('content')

	<div class="ui grid">
		<div class="six wide column">
			<div class="ui blue segment">
				<div class="ui red small message">Red</div>
				<div class="ui orange small message">Orange</div>
				<div class="ui yellow small message">Yellow</div>
				<div class="ui olive small message">Olive</div>
				<div class="ui green small message">Green</div>
				<div class="ui teal small message">Teal</div>
				<div class="ui blue small message">Blue</div>
				<div class="ui violet small message">Violet</div>
				<div class="ui purple small message">Purple</div>
			</div>
		</div>
		<div class="ten wide column"><div id='calendar'></div></div>
	</div>


@endsection

@section('javascript')
	<script src="{{\Pingpong\Modules\Facades\Module::asset('calendar:js/vendor.js')}}"></script>
	<script>
		$(document).ready(function() {

			var date = new Date();
			var d = date.getDate(),
					m = date.getMonth(),
					y = date.getFullYear();

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
				//Random default events
				events: [
					{
						title: 'All Day Event',
						start: new Date(y, m, 1),
						className: "red" //red
					},
					{
						title: 'Long Event',
						start: new Date(y, m, d - 5),
						end: new Date(y, m, d - 2),
						className: "red" //red
					},
					{
						title: 'Meeting',
						start: new Date(y, m, d, 10, 30),
						allDay: false,
						className: "red" //red
					},
					{
						title: 'Lunch',
						start: new Date(y, m, d, 12, 0),
						end: new Date(y, m, d, 14, 0),
						allDay: false,
						className: "red" //red
					},
					{
						title: 'Birthday Party',
						start: new Date(y, m, d + 1, 19, 0),
						end: new Date(y, m, d + 1, 22, 30),
						allDay: false,
						className: "red" //red
					},
					{
						id: 999,
						title: 'Repeating Event',
						start: new Date(y, m, d + 1, 19, 0)
					},
					{
						id: 999,
						title: 'Repeating Event',
						start:new Date(y, m, d + 8, 19, 0)
					},
					{
						title: 'Click for Google',
						start: new Date(y, m, 28),
						end: new Date(y, m, 29),
						url: 'http://google.com/',
						className: "red" //red
					}
				],
				editable: true,
				droppable: true, // this allows things to be dropped onto the calendar !!!
				drop: function (date, allDay) { // this function is called when something is dropped

					// retrieve the dropped element's stored Event Object
					var originalEventObject = $(this).data('eventObject');

					// we need to copy it, so that multiple events don't have a reference to the same object
					var copiedEventObject = $.extend({}, originalEventObject);

					// assign it the date that was reported
					copiedEventObject.start = date;
					copiedEventObject.allDay = allDay;
					copiedEventObject.backgroundColor = $(this).css("background-color");
					copiedEventObject.borderColor = $(this).css("border-color");

					// render the event on the calendar
					// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
					$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

					// is the "remove after drop" checkbox checked?
					if ($('#drop-remove').is(':checked')) {
						// if so, remove the element from the "Draggable Events" list
						$(this).remove();
					}

				}
			});

		});

	</script>


@endsection
