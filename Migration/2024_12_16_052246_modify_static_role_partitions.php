<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('l_roles_statics_partitions', function (Blueprint $table) {
			$table->boolean('is_active')->default(true);
			$table->unsignedBigInteger('creator_id')->nullable();
			$table->unsignedBigInteger('editor_id')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('l_roles_statics_partitions', function (Blueprint $table) {
			$table->dropColumn('is_active');
			$table->dropColumn('creator_id');
			$table->dropColumn('editor_id');
			$table->dropTimestamps();
		});
	}
};