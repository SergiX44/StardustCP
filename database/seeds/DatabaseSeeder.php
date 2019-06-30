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
		    'name' => 'admin',
		    'email' => 'a@a.a',
		    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
	    ]);
        // $this->call(UsersTableSeeder::class);
    }
}
