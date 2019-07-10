<?php

namespace Modules\Database\Controllers;

use Core\Http\Controllers\Controller;

class TestController extends Controller
{

	public function test()
	{
		return view('database::test');
	}

	public function configure()
	{
		return view('database::config');
	}
}