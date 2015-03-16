<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedule', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('event');
			$table->time('time');
			$table->integer('amount');
			$table->integer('user');
			$table->boolean('enable')->default(1);
			$table->rememberToken();
			$table->timestamps();

			$table->unique(['time', 'user']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('schedule');
	}

}
