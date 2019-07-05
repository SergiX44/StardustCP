<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

	/**
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function root()
	{
		return view('welcome');
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
