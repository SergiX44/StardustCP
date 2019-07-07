<?php

namespace Modules\Web\Controllers;

use Core\Http\Controllers\Controller;

class TestController extends Controller
{

	public function test()
	{
		return view('web::test');
	}

	public function configure()
	{
		return view('web::config');
	}
}