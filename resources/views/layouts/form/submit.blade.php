<div class="form-group row mb-0">
    <div class="col-md-8 offset-md-3">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save fa-fw"></i> @lang('misc.save')</button>
        <a href="{{ route($cancelRoute ?? 'core.root') }}" class="btn btn-secondary">@lang('misc.cancel')</a>
    </div>
</div>
