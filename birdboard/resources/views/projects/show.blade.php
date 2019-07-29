@extends ('layouts.app')

@section('content')

	<h1>{{ $project->title }}</h1>
	<div>{{ $project->description }}</div>
	<a href="/projects">Go back</a>

	@foreach ($project->tasks as $task)

		<li>
			<p>{{ $task->body }}</p>
		</li>

	@endforeach

	<form action="{{ $project->path() . '/tasks' }}" method="POST">
		
		@csrf

		<input class="input" name="body" type="text" placeholder="Add new task...">

	</form>

@endsection