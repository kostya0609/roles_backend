<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::statement("
			create view l_roles_statics_partitions_view as
				select
					CONCAT(id, 1) as item_id,
					id,
					title,
					is_active,
					parent_id,
					'partition' as type,
					creator_id,
					editor_id,
					created_at,
					updated_at
				from l_roles_statics_partitions
				union all
				select
					CONCAT(id, 2) as item_id,
					id,
					title,
					is_active,
					partition_id as parent_id,
					'role' as type,
					creator_id,
					editor_id,
					created_at,
					updated_at
				from l_roles_statics
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		\DB::statement("drop view if exists l_roles_statics_partitions_view");
	}
};