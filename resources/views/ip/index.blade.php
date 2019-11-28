@extends('layouts.app')

@section('title', 'IP Addresses')

@section('header')
    <h1>IP Addresses</h1>
    @include('layouts.breadcrumb', ['b'=> ['Configuration' => route('core.configuration'), 'IP Addresses']])
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('core.ip.create') }}" class="btn btn-success"><i class="fas fa-plus fa-fw"></i> Add new IP</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-md">
                            <tbody>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Address</th>
                                <th>Added</th>
                                <th></th>
                            </tr>
                            @foreach($ips as $ip)
                            <tr>
                                <td>{{ $ip->id }}</td>
                                <td>{{ $ip->type }}</td>
                                <td>{{ $ip->address }}</td>
                                <td>{{ $ip->created_at }}</td>
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
                    {{ $ips->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
