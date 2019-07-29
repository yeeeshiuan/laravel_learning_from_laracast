@extends ('layouts.app')

@section('cdn')

	<!-- Latest CSS files for bulma and font-awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">

@endsection

@section('content')

	<div class="column ">
        <a href="/projects/create/">New Project</a>
	</div>

	<ul>

		@forelse ($projects as $project)

			<li>
				<a href="{{ $project->path() }}">{{ $project->title }}</a>
			</li>

		@empty

			<li>No projects yet.</li>

		@endforelse
	</ul>

@endsection
