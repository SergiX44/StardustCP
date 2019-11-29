<?php

namespace Core\Http\Controllers;

use Core\Models\IP;
use Illuminate\Http\Request;

class IPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $ips = IP::paginate(25);

        return view('ip.index', [
            'ips' => $ips
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('ip.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $type = ($request->get('type') === 'ipv6') ? 'ipv6' : 'ipv4';
        $this->validate($request, [
            'type' => 'required',
            'address' => 'required|unique:system_ips,address|'.$type
        ]);

        $ip = new IP();
        $ip->fill($request->all());
        $ip->save();

        session()->flash('status', ['success' => 'IP address added.']);

        return redirect()->route('core.ip.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  IP  $ip
     * @return void
     */
    public function show(IP $ip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Core\IP  $iP
     * @return \Illuminate\Http\Response
     */
    public function edit(IP $ip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  IP  $ip
     * @return void
     */
    public function update(Request $request, IP $ip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  IP  $ip
     * @return void
     */
    public function destroy(IP $ip)
    {
        //
    }
}
