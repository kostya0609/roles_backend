# ФМ "Управления ролями"

## Установка

* В папку с модулями (app/Modules) склонировать модуль
  ```php
  git clone https://gitlab.bitrix.bsi.local/bitrix24/roles_backend.git Roles
  ```
* Прописать модуль Roles в config/module.php
* Выполнить миграции
  ```bash
  php artisan module:migrate Roles
  ```
* Заполнить модуль начальными данными
  ```bash
  php artisan roles:seeding-initial
  ```

При необходимости модуль можно заполнить тестовыми данными (выполнять после вышеописанных шагов):

```bash
php artisan roles:seeding-test
```

## Статичные роли

### Получения участников статичной роли

```php
<?php
use App\Modules\Roles\Facades\StaticRoleFacade;

$users = StaticRoleFacade::getUsersByRoleId(1);
```

## Динамические роли

### Получение участников динамической роли

```php
<?php
use App\Modules\Roles\Enums\DynamicRole;
use App\Modules\Roles\Facades\DynamicRoleFacade;

$users = DynamicRoleFacade::getUsersByRoleId(DynamicRole::DIRECT_SUPERVISOR, 14317);
```
