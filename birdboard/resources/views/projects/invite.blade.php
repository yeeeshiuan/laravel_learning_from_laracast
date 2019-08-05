<form method="POST" action="{{ $project->path() . '/invitations' }}">

	@csrf
	
	<input type="email" name="email" placeholder="Email address" />

	<button type="submit">Invite</button>

</form>

@include('errors', ['bag' => 'invitations'])