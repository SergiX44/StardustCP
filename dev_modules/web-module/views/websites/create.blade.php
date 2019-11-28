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
                                @form_radio([
                                'name' => 'is_domain',
                                'label' => 'From',
                                'items' => [['value' => 'true', 'text' => 'Domain', 'active' => true], ['value' => 'false', 'text' => 'Subdomain']],
                                ])

                                @form_select([
                                'label' => 'Parent Domain',
                                'name' => 'parent_domain',
                                'options' => [
                                1 => 'google.com',
                                12 => 'amazon.com'
                                ]
                                ])

                                @form_input(['name' => 'domain_value', 'label' => 'Domain or Subdomain Name'])

                                @form_select(['name' => 'ipv4', 'label' => 'IPv4', 'options' => $ipv4])

                                @form_select(['name' => 'ipv6', 'label' => 'IPv6', 'options' => $ipv6])

                                @form_switch(['name' => 'php_enabled', 'label' => 'PHP Enabled'])

                                @form_switch(['name' => 'ssl_enabled', 'label' => 'SSL Enabled', 'checked' => true])
                            </div>
                            <div class="tab-pane fade" id="webserver-conf">
                                Sed sed metus vel lacus hendrerit tempus. Sed efficitur velit tortor, ac efficitur est lobortis quis. Nullam lacinia metus erat, sed fermentum justo rutrum ultrices. Proin quis iaculis tellus. Etiam ac vehicula eros, pharetra consectetur dui.
                            </div>
                            <div class="tab-pane fade" id="php-conf">
                                Vestibulum imperdiet odio sed neque ultricies, ut dapibus mi maximus. Proin ligula massa, gravida in lacinia efficitur, hendrerit eget mauris. Pellentesque fermentum, sem interdum molestie finibus, nulla diam varius leo, nec varius lectus elit id dolor.
                            </div>
                        </div>

                        @form_submit(['cancel' => 'web.websites.index'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
