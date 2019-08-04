<?php


namespace Isofman\LaravelSetable\Commands;


use Illuminate\Console\Command;

class Set extends Command
{
    protected $signature = 'setable:set {key} {value} {type?} {--force}';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $result = app('setable')->set(
            $this->argument('key'),
            $this->argument('value'),
            $this->argument('type'),
            $this->option('force')
        );

        if($result === '__not_exists') {
            $this->error('Setting for "'.$this->argument('key').'" does not exists. Add --force to create new.');
        }

        $this->info('done!');
    }
}