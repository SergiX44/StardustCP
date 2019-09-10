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

                        @form_select(['name' => 'ipv4', 'label' => 'IPv4', 'options' => []])
                        
                        @form_select(['name' => 'ipv6', 'label' => 'IPv6', 'options' => []])

                        @form_switch(['name' => 'php_enabled', 'label' => 'PHP Enabled'])

                        @form_switch(['name' => 'ssl_enabled', 'label' => 'SSL Enabled', 'checked' => true])

                        @form_submit(['cancel' => 'web.websites.index'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection