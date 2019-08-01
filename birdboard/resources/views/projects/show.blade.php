@extends ('layouts.app')

@section('content')

	<h1>{{ $project->title }}</h1>
	<div>{{ $project->description }}</div>
	<a href="/projects">Go back</a>

	@foreach ($project->tasks as $task)

		<form method="POST" action="{{ $task->path() }}">

			@method('PATCH')

			@csrf

			<input value="{{ $task->body }}" name="body" />

			<input type="checkbox" 
					name="completed" 
					onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }} />

		</form>

	@endforeach

	<form action="{{ $project->path() . '/tasks' }}" method="POST">
		
		@csrf

		<input class="input" name="body" type="text" placeholder="Add new task...">

	</form>

	<a href="{{ $project->path() . '/edit' }}">Edit Project</a>

	<form action="{{ $project->path() }}" method="POST">
		
		@csrf

		@method('PATCH')

		<textarea name="notes">{{ $project->notes }}</textarea>

		<button type="submit">Save</button>

	</form>

@endsection