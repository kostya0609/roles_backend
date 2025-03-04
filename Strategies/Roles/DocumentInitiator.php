<?php
namespace App\Modules\Roles\Strategies\Roles;

use App\Modules\Roles\Strategies\RoleInterface;
use Illuminate\Support\Collection;
use App\Modules\Roles\Models\User;

/**
 * Роль инициатора документа обязательно требует передачи дополнительных параметров $params['document_initiator_id']
 */
class DocumentInitiator implements RoleInterface
{
	public function execute(int $user_id, array $params = []): Collection
	{
		if (!isset($params['document_initiator_id'])) {
			throw new \InvalidArgumentException('Не указан идентификатор инициатора документа');
		}

		$document_initiator_id = intval($params['document_initiator_id']);

		if (!is_int($document_initiator_id) || $document_initiator_id <= 0) {
			throw new \InvalidArgumentException('Идентификатор инициатора документа должен быть положительным целым числом');
		}

		$document_initiator = User::findOrFail($document_initiator_id);

		return collect([$document_initiator]);
	}
}