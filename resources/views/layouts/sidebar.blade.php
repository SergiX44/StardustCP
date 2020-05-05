@inject('menu', 'menu')
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('core.root') }}">{{ config('app.name') }}</a>
        </div>
        {!! $menu->render() !!}
    </aside>
</div>
