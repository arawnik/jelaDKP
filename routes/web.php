<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Auth routes.
Route::get('/login', 'JelaAuthController@LoginView')->name('login');
Route::post('/login', 'JelaAuthController@authenticate')->name('authenticate');
Route::get('/logout', 'JelaAuthController@Logout')->name('logout');
//Route::get('/register', 'JelaAuthController@Todo')->name('register');

//Common routes for unauthenticated user.
Route::get('/', 'CharacterController@index')->name('home');
Route::get('/raids', 'RaidController@index')->name('raids');
Route::get('/stats', 'CharacterController@stats')->name('stats');

//Specific data view for unauthenticated user.
Route::get('char/{id}', 'CharacterController@char')->name('char/{id}');
Route::get('raid/{id}', 'RaidController@raid')->name('raid/{id}');

//Not implemented
//Route::post('/password/email', 'JelaAuthController@Todo')->name('email_pass');
//Route::post('/password/reset', 'JelaAuthController@Todo')->name('reset_pass');

//Add all of the authenticated routes within the group.
Route::group(['middleware' => ['auth']], function() {
	//Manage raid
	Route::post('/raid_management', 'RaidController@createRaid')->name('create_raid');
	Route::post('/update_raid', 'RaidController@updateRaid')->name('update_raid');
	Route::post('/delete_raid', 'RaidController@deleteRaid')->name('delete_raid');
	Route::get('/raid_management', 'RaidController@raidManagement')->name('raid_management');
	Route::get('/modify_raid/{id}', 'RaidController@modifyRaid')->name('modify_raid/{id}');
	
	//Manage raid related data
	Route::post('/modify_raid/attendance', 'RaidController@updateRaidAttendance')->name('update_raid_attendance');
	Route::post('/modify_raid/item', 'RaidController@createRaidItem')->name('create_raid_item');
	Route::post('/delete_raid/item', 'RaidController@deleteRaidItem')->name('delete_raid_item'); //Had to use post and different name for some weird issues with routing... (couldnt get delete method to work..)
	Route::post('/modify_raid/adjustment', 'RaidController@createRaidAdjustment')->name('create_raid_adjustment');
	Route::post('/delete_raid/adjustment', 'RaidController@deleteRaidAdjustment')->name('delete_raid_adjustment');
	
	//Manage character
	Route::post('/character_management', 'CharacterController@createCharacter')->name('create_character');
	Route::post('/update_character', 'CharacterController@updateCharacter')->name('update_character');
	Route::post('/delete_character', 'CharacterController@deleteCharacter')->name('delete_character');
	Route::get('/character_management', 'CharacterController@characterManagement')->name('character_management');
	Route::get('/modify_character/{id}', 'CharacterController@modifyCharacter')->name('modify_character/{id}');
	
	//Manage normalization
	Route::post('/normalization_management', 'NormalizationController@createNormalization')->name('create_normalization');
	Route::post('/update_normalization_points', 'NormalizationController@updateNormalizationPoints')->name('update_normalization_points');
	Route::post('/delete_normalization', 'NormalizationController@deleteNormalization')->name('delete_normalization');
	Route::get('/normalization_management', 'NormalizationController@normalizationManagement')->name('normalization_management');
	Route::get('/modify_latest_normalization', 'NormalizationController@modifyLatestNormalization')->name('modify_latest_normalization');
});
