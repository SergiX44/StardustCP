<div class="form-group row" id="{{ $name }}-entry">
    <label for="{{ $name }}" class="col-sm-3 col-form-label text-md-right">@lang(($label ?? ucfirst($name)))</label>
    <div class="col-md-6">
        <textarea id="{{ $name }}" class="form-control @error($name) is-invalid @enderror" name="{{ $name }}" {{ $attr ?? '' }}>
        {{ old($name, ($value ?? null)) }}
        </textarea>
        <span class="invalid-feedback">
            @error($name)
            {{ $message }}
            @enderror
            @isset($invalid)
                @lang($invalid)
            @endisset
        </span>
    </div>
</div>
