<div class="field">
  <label class="label" for="title">Title</label>

  <div class="control">
    <input class="input" value="{{ $project->title }}" type="text" name="title" placeholder="Titile" required>
  </div>
</div>

<div class="field">
  <label class="label" for="description">Description</label>

  <div class="control">
    <textarea class="textarea" name="description" placeholder="Description" required>{{ $project->description }}
	</textarea>
  </div>
</div>

<div class="field">
	<div class="control">
	  <button class="button is-primary" type="submit">{{ $formSubmitText }}</button>
	  <a href="{{ $project->path() }}">Cancel</a>
	</div>
</div>

@if ($errors->any())
<div class="field">

	@foreach ($errors->all() as $error)

		<li>{{ $error }}</li>

	@endforeach

</div>
@endif