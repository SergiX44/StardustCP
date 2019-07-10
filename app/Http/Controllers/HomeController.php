<?php

namespace Core\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

	/**
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function root()
	{
		return redirect()->route('core.home');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		return view('dashboard');
	}
}
