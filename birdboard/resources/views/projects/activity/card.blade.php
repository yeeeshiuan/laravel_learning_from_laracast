<div>
	
	<ul>

		@foreach($project->activity as $activity)

			<li>

				@include("projects.activity.{$activity->description}")

				{{ $activity->created_at->diffForHumans(null, true) }}

			</li>

		@endforeach

	</ul>

	<footer>
		
		<form method="POST" action="{{ $project->path() }}">

			@method('DELETE')

			@csrf
			
			<button type="submit">Delete</button>

		</form>

	</footer>

</div>