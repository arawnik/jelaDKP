<?php

use Illuminate\Database\Seeder;

class DefaultWowDataSeeder extends Seeder {
	/**
	 * Run the Wow-specific database seeds.
	 *
	 * @return void
	 */
	public function run() {
		//Add the default classes.
		DB::table('classes')->insert([
			['class_name' => 'Druid', 'class_color' => 'FF7D0A'],
			['class_name' => 'Hunter', 'class_color' => 'ABD473'],
			['class_name' => 'Mage', 'class_color' => '69CCF0'],
			['class_name' => 'Paladin', 'class_color' => 'F58CBA'],
			['class_name' => 'Priest', 'class_color' => 'FFFFFF'],
			['class_name' => 'Rogue', 'class_color' => 'FFF569'],
			['class_name' => 'Shaman', 'class_color' => '0070DE'],
			['class_name' => 'Warlock', 'class_color' => '9482C9'],
			['class_name' => 'Warrior', 'class_color' => 'C79C6E']
		]);
		//Add the default roles.
		DB::table('roles')->insert([
			['role_name' => 'Tank'],
			['role_name' => 'Healer'],
			['role_name' => 'Dps']
		]);
	}
}
