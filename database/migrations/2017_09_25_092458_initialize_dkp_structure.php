<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitializeDkpStructure extends Migration {

	/**
	 * Run the migrations.
	 *
	* @return void
	*/
	public function up() {
		//Create classes table.
		Schema::create('classes', function (Blueprint $table) {
			$table->increments('class_id');
			$table->string('class_name');
			$table->string('class_color');
		});

		//Create roles table.
		Schema::create('roles', function (Blueprint $table) {
			$table->increments('role_id');
			$table->string('role_name');
		});

		//Create characters table.
		Schema::create('characters', function (Blueprint $table) {
			$table->increments('char_id');
			$table->string('char_name');
			$table->integer('char_class')->unsigned();
			$table->foreign('char_class')
				->references('class_id')->on('classes')
				->onUpdate('cascade')
				->onDelete('restrict');
			$table->integer('char_role')->unsigned();
			$table->foreign('char_role')
				->references('role_id')->on('roles')
				->onUpdate('cascade')
				->onDelete('restrict');
		});
		
		//Create normalization table.
		Schema::create('normalization', function (Blueprint $table) {
			$table->increments('normalization_id');
			$table->integer('normalization_adder');
			$table->integer('normalization_percent');
			$table->string('normalization_comment');
			$table->timestamp('normalization_date')->useCurrent = true;
		});

		//Create normalization points table.
		Schema::create('normalization_points', function (Blueprint $table) {
			$table->integer('normalization_id')->unsigned();
			$table->foreign('normalization_id')
				->references('normalization_id')->on('normalization')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('char_id')->unsigned();
			$table->foreign('char_id')
				->references('char_id')->on('characters')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->double('normalization_amount', 15, 4);
			//Add keys
			$table->primary(['normalization_id', 'char_id']);
		});
		
		//Create raids table.
		Schema::create('raids', function (Blueprint $table) {
			$table->increments('raid_id');
			$table->double('raid_value', 15, 4);
			$table->string('raid_comment');
			$table->dateTime('raid_date');
			$table->timestamp('raid_added')->useCurrent = true;
		});

		//Create raid adjustments table.
		Schema::create('raid_adjustments', function (Blueprint $table) {
			$table->integer('adjust_raid')->unsigned();
			$table->foreign('adjust_raid')
				->references('raid_id')->on('raids')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('adjust_character')->unsigned();
			$table->foreign('adjust_character')
				->references('char_id')->on('characters')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->double('adjust_value', 15, 4);
			$table->string('adjust_comment');
			//Add keys
			$table->primary(['adjust_raid', 'adjust_character']);
		});
		
		//Create raid attends table.
		Schema::create('raid_attends', function (Blueprint $table) {
			$table->integer('attend_raid')->unsigned();
			$table->foreign('attend_raid')
				->references('raid_id')->on('raids')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('attend_character')->unsigned();
			$table->foreign('attend_character')
				->references('char_id')->on('characters')
				->onUpdate('cascade')
				->onDelete('cascade');
			//Add keys
			$table->primary(['attend_raid', 'attend_character']);
		});

		//Create points used table.
		Schema::create('points_used', function (Blueprint $table) {
			$table->increments('use_id');
			$table->integer('use_raid')->unsigned();
			$table->foreign('use_raid')
				->references('raid_id')->on('raids')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->integer('use_character')->unsigned();
			$table->foreign('use_character')
				->references('char_id')->on('characters')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->double('use_amount', 15, 4);
			$table->string('use_desc');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		//Drop all of the tables added here, if they exist.
		Schema::dropIfExists('points_used');
		Schema::dropIfExists('raid_attends');
		Schema::dropIfExists('raid_adjustments');
		Schema::dropIfExists('raids');
		Schema::dropIfExists('normalization_points');
		Schema::dropIfExists('normalization');
		Schema::dropIfExists('characters');
		Schema::dropIfExists('roles');
		Schema::dropIfExists('classes');
	}
}
