<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('l_roles_statics_participants', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('static_role_id');
			$table->unsignedBigInteger('user_id');

			$table
				->foreign('static_role_id')
				->references('id')
				->on('l_roles_statics')
				->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('l_roles_statics_participants');
	}
};
