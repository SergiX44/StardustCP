@if (session('status'))
    @if(is_array(session('status')))
        @foreach(session('status') as $type => $status)
            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                {{ $status }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @else
        <div class="alert alert-dark alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endif
