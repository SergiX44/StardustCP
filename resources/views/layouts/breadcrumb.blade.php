<div class="section-header-breadcrumb">
    <div class="breadcrumb-item">
         <a href="{{ route('core.home') }}"><i class="fas fa-home fa-fw"></i> Home</a>
    </div>
    @foreach($b as $name => $url)
        @if(!is_numeric($name))
            <div class="breadcrumb-item">
                <a href="{{ $url }}">{{ $name }}</a>
            </div>
        @else
            <div class="breadcrumb-item active">{{ $url }}</div>
        @endif
    @endforeach
</div>