<?php

use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder {

	/**
	 * Run the users database seeds.
	 *
	 * @return void
	 */
	public function run() {
		//Add the 2 default users.
		DB::table('users')->insert([
			[
				'name' => 'username',
				'email' => 'test@test.com',
				'password' => bcrypt('password'),
			]
		]);
	}
}
