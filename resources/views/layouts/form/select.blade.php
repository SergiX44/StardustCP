<div class="form-group row" id="{{ $name }}-entry">
    <label for="{{ $name }}" class="col-sm-3 col-form-label text-md-right">@lang(($label ?? ucfirst($name)))</label>
    <div class="col-md-6">
        <select id="{{ $name }}" class="form-control select2 @error($name) is-invalid @enderror" name="{{ $name }}" {{ $attr ?? '' }}>
            @if(isset($selected) && !$selected)
                <option value="" selected>(Not set)</option>
            @endif
            @foreach($options as $value => $text)
                <option value="{{ $value }}" {{ isset($selected) && $value === $selected ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
        </select>
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
