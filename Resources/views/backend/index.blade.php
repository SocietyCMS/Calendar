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
				<div class="content">
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


@endsection

@section('styles')
	<link media="all" type="text/css" rel="stylesheet" href="{{\Pingpong\Modules\Facades\Module::asset('calendar:css/Calendar.css')}}">
@endsection

@section('javascript')
	<script src="{{\Pingpong\Modules\Facades\Module::asset('calendar:js/vendor.js')}}"></script>
	<script>
		$('.ui.accordion').accordion({exclusive:false});

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
				firstDay:1,
				events: function(start, end, timezone, callback) {
					var resource = Vue.resource('{{apiRoute('v1', 'api.calendar.event.index')}}');
					resource.get({start:start.format(), end:end.format()}).then(function (response) {
						callback(response.data.data)
					});

				},
				editable: true,
				droppable: true
			});

		});

	</script>


@endsection
