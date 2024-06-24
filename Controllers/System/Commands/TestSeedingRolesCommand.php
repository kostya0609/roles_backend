<?php
namespace App\Modules\Roles\Controllers\System\Commands;

use Illuminate\Console\Command;
use App\Modules\Roles\Seeders\Test\DatabaseSeeder;

class TestSeedingRolesCommand extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'roles:seeding-test';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Заполнения модуля ролей тестовыми данными';

    /**
     * Выполнить консольную команду.
     *
     * @return mixed
     */
    public function handle(DatabaseSeeder $seeder)
    {
        $this->info('Заполнение таблиц модуля тестовыми данными');
        $seeder->run();
    }
}