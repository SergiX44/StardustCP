@extends('layouts.app')

@section('title', 'Add new website')

@section('header')
    <h1>Add new website</h1>
    @include('layouts.breadcrumb', ['b'=> ['Websites' => route('web.websites.index'), 'Add new website']])
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('web.websites.store') }}">
                        @csrf

                        <ul class="nav nav-pills nav-justified mb-3">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#webspace"><i class="fas fa-globe"></i> Webspace</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#webserver-conf" role="tab"><i class="fas fa-server"></i> Webserver settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#php-conf"><i class="fab fa-php"></i> PHP settings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#php-conf"><i class="fas fa-tape"></i> Backups</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#php-conf"><i class="fab fa-git-alt"></i> Git Deploy</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent2">
                            <div class="tab-pane fade show active" id="webspace">
                                @formRadio([
                                'name' => 'type',
                                'label' => 'From',
                                'items' => [['value' => 'domain', 'text' => 'Domain', 'active' => true], ['value' => 'subdomain', 'text' => 'Subdomain']],
                                ])

                                @formSelect([
                                'label' => 'Parent Domain',
                                'name' => 'parent_domain',
                                'options' => $domains,
                                'selected' => false
                                ])

                                @formInput(['name' => 'domain', 'label' => 'Domain or Subdomain Name'])

                                @formSelect(['name' => 'ipv4_id', 'label' => 'IPv4', 'options' => $ipv4])

                                @formSelect(['name' => 'ipv6_id', 'label' => 'IPv6', 'options' => $ipv6])

                                @formSwitch(['name' => 'php_enabled', 'label' => 'PHP Enabled'])

                                @formSwitch(['name' => 'ssl_enabled', 'label' => 'SSL Enabled'])
                            </div>
                            <div class="tab-pane fade" id="webserver-conf">
                                @formTextarea(['name' => 'webserver_directives', 'label' => 'Webserver directives'])
                            </div>
                            <div class="tab-pane fade" id="php-conf">
                                @formTextarea(['name' => 'php_directives', 'label' => 'PHP directives'])
                            </div>
                        </div>

                        @formSubmit(['cancel' => 'web.websites.index'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
@endsection
