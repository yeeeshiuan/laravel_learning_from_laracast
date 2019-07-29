@extends ('layouts.app')

@section('content')

	<h1>{{ $project->title }}</h1>
	<div>{{ $project->description }}</div>
	<a href="/projects">Go back</a>

	@forelse ($project->tasks as $task)

		<li>
			<p>{{ $task->body }}</p>
		</li>

	@empty

		<li>No task yet.</li>

	@endforelse

@endsection