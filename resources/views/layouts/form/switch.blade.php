<div class="form-group row" id="{{ $name }}-entry">
    <label for="{{ $name }}" class="col-sm-3 col-form-label text-md-right">@lang(($label ?? ucfirst($name)))</label>
    <div class="col-md-6">
        <label class="custom-switch mt-2">
            <input type="checkbox" id="{{ $name }}" name="{{ $name }}"
                   class="custom-switch-input @error($name) is-invalid @enderror"
                   onchange="$('#{{ $name }}-desc').text($(this).is(':checked') ? 'On' : 'Off')" {{ isset($checked) && $checked ? 'checked' : '' }}>
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description" id="{{ $name }}-desc">{{ $text ?? isset($checked) && $checked ? 'On' : 'Off' }}</span>
        </label>
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
