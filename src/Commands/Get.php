<?php


namespace Isofman\LaravelSetable\Commands;


use Illuminate\Console\Command;

class Get extends Command
{
    protected $signature = 'setable:get {keys*}';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $keys = $this->argument('keys');

        $keys = count($keys) === 1 ? $keys[0] : $keys;

        $data = app('setable')->get($keys);

        dd($data);
    }
}