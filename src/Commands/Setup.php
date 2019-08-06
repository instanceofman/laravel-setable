<?php


namespace Isofman\LaravelSetable\Commands;


use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Setup extends Command
{
    protected $signature = 'setable:setup';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $table = config('setable.table');

        if(!$table) {
            $this->error("Please run `php artisan vendor:publish` to copy config file before setup.");
            return false;
        }

        if ($exists = Schema::hasTable($table)) {
            return $this->success();
        }

        $this->runMigration($table);

        return $this->success();
    }

    protected function success()
    {
        $this->info('Setup setable successfully!');
        return true;
    }

    protected function runMigration($table)
    {
        Schema::create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('opt_key', 80)->unique();
            $table->string('opt_type', 30)->default('string');
            $table->text('opt_value');
            $table->timestamps();
        });
    }
}