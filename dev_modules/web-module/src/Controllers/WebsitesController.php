<?php

namespace Modules\Web\Controllers;

use Core\Http\Controllers\Controller;
use Core\Models\IP;
use Illuminate\Http\Request;

class WebsitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('web::websites.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ipv4 = IP::select('id', 'address')
            ->where('type', 'ipv4')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->address];
            });

        $ipv6 = IP::select('id', 'address')
            ->where('type', 'ipv6')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->address];
            });

        $ipv6->prepend('(Not set)', '');

        return view('web::websites.create', [
            'ipv4' => $ipv4,
            'ipv6' => $ipv6,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'in:domain,subdomain',
            'domain_value' => 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/im',
            'ipv4' => 'required|exists:system_ips,id',
            'ipv6' => 'present'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
