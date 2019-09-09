<div class="form-group row">
    <label for="{{ $name }}" class="col-sm-3 col-form-label text-md-right">@lang(($text ?? ucfirst($name)))</label>
    <div class="col-md-6">
        <input id="{{ $name }}" type="{{ $type ?? 'text' }}" class="form-control @error($name) is-invalid @enderror" name="{{ $name }}" value="{{ old($name, ($value ?? null)) }}" {{ $attr ?? '' }}>
        <span class="invalid-feedback">
            @error($name)
                {{ $message }}
            @else
                @lang('auth.email_required')
            @enderror
        </span>
    </div>
</div>