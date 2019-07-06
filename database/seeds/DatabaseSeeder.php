<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		\App\Models\User::create([
			'username' => 'admin',
			'name' => 'admin',
			'surname' => 'god',
			'email' => 'a@a.a',
			'password' => \Illuminate\Support\Facades\Hash::make('aaa'), // password
		]);
		// $this->call(UsersTableSeeder::class);
	}
}
