<div class="form-group row" id="{{ $name }}-entry">
    <label for="{{ $name }}" class="col-sm-3 col-form-label text-md-right">@lang(($label ?? ucfirst($name)))</label>
    <div class="col-md-6">
        <div class="selectgroup">
            @foreach($items as $item)
                <label class="selectgroup-item">
                    <input type="radio" name="{{ $name }}" value="{{ $item['value'] }}" class="selectgroup-input radio-{{ $name }}" autocomplete="off" {{ isset($item['active']) && $item['active']  ? 'checked': '' }}>
                    <span class="selectgroup-button">@lang(($item['text'] ?? ucfirst($name)))</span>
                </label>
            @endforeach
        </div>
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
