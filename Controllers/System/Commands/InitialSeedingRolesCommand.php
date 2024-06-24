<?php
namespace App\Modules\Roles\Controllers\System\Commands;

use Illuminate\Console\Command;
use App\Modules\Roles\Seeders\Initial\DatabaseSeeder;

class InitialSeedingRolesCommand extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'roles:seeding-initial';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Заполнения модуля ролей начальными данными';

    /**
     * Выполнить консольную команду.
     *
     * @return mixed
     */
    public function handle(DatabaseSeeder $seeder)
    {
        $this->info('Заполнение таблиц модуля начальными данными');
        $seeder->run();
    }
}