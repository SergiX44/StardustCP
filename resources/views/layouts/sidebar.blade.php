@inject('menu', 'menu')
<!-- Sidebar outter -->
<div class="main-sidebar">
    <!-- sidebar wrapper -->
    <aside id="sidebar-wrapper">
        <!-- sidebar brand -->
        <div class="sidebar-brand">
            <a href="{{ route('root') }}">{{ config('app.name') }}</a>
        </div>
        <!-- sidebar menu -->
        {!! $menu->render() !!}
    </aside>
</div>