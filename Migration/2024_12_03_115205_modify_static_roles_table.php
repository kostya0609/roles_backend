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
		Schema::table('l_roles_statics', function (Blueprint $table) {
			$table->unsignedBigInteger('partition_id')->nullable();
			$table->text('description')->nullable()->change();


			$table
				->foreign('partition_id')
				->references('id')
				->on('l_roles_statics_partitions')
				->restrictOnDelete();
		});
	}

	/**z
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('l_roles_statics', function (Blueprint $table) {
			$table->dropForeign(['partition_id']);

			$table->dropColumn('partition_id');
			$table->text('description')->nullable(false)->change();
			
		});
	}
};