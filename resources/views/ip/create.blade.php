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
                    <form method="post" action="{{ route('core.ip.store') }}">
                        @csrf

                        @form_radio([
                        'name' => 'type',
                        'label' => 'Type',
                        'items' => [['value' => 'ipv4', 'text' => 'IPv4', 'active' => true], ['value' => 'ipv6', 'text' => 'IPv6']],
                        ])

                        @form_input(['name' => 'address', 'label' => 'IP Address'])

                        @form_submit(['cancel' => 'core.ip.index'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
