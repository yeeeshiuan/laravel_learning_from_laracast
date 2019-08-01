@extends ('layouts.app')

@section('cdn')

	<!-- Latest CSS files for bulma and font-awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">

@endsection

@section('content')

	<h1 class="heading is-1">Edit Your Project</h1>

	<form method="POST" action="{{ $project->path() }}">

		@csrf

		@method('PATCH')

		@include('projects.form', ['formSubmitText' => 'Update'])

	</form>

@endsection