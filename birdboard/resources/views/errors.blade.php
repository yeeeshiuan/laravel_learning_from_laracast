@if ($errors->{ $bag ?? 'default' }->any())
<div class="field">

    @foreach ($errors->{ $bag ?? 'default' }->all() as $error)

        <li>{{ $error }}</li>

    @endforeach

</div>
@endif