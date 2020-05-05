@extends('layouts.app')

@section('title', 'Websites')

@section('header')
    <h1>Websites</h1>
    @include('layouts.breadcrumb', ['b'=> ['Websites']])
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('web.websites.create') }}" class="btn btn-success"><i class="fas fa-plus fa-fw"></i> Create Website</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-md">
                            <tbody>
                            <tr>
                                <th>#</th>
                                <th>doc root</th>
                                <th></th>
                            </tr>
                            @foreach($webspaces as $w)
                                <tr>
                                    <td>{{ $w->id }}</td>
                                    <td>{{ $w->document_root }}</td>
                                    <td class="text-right">
                                        <a href="#" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>
                                        <a href="#" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    {{ $webspaces->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
