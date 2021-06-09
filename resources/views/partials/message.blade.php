@if(session()->has('msg'))
<div class="alert alert-{{ session('type') }}">
    {!! session('msg') !!}
    <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">
            <i class="tim-icons icon-simple-remove"></i>
        </span>
    </button>
    </div>
@endif
