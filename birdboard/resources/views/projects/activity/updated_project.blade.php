@if (count($activity->changes['after']) == 1)
	You update the {{ key($activity->changes['after']) }} of the project
@else
	You update the project
@endif