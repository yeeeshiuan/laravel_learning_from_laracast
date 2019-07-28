<!DOCTYPE html>
<html>
<head>
	<title></title>
	<!-- Latest CSS files for bulma and font-awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css">
</head>
<body>
	<form method="POST" action="/projects" class="container" style="padding-top: 40px">

		@csrf

		<h1 class="heading is-1">Create a Project</h1>

		<div class="field">
		  <label class="label" for="title">Title</label>

		  <div class="control">
		    <input class="input" type="text" name="title" placeholder="Titile">
		  </div>
		</div>

		<div class="field">
		  <label class="label" for="description">Description</label>

		  <div class="control">
		    <textarea class="textarea" name="description" placeholder="Description"></textarea>
		  </div>
		</div>

		<div class="field">
			<div class="control">
			  <button class="button is-primary" type="submit">Create Project</button>
			</div>
		</div>

	</form>
</body>
</html>