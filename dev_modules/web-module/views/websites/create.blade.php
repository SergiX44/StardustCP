@extends('layouts.app')

@section('title', 'Add new Website')

@section('header')
    <h1>Add new Website</h1>
    @include('layouts.breadcrumb', ['b'=> ['Websites' => route('web.websites.index'), 'Add new Website']])
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('web.websites.store') }}">
                        @csrf

                        @form_input(['name' => 'domain'])@endform_input

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection