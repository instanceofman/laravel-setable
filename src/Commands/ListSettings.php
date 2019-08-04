<?php


namespace Isofman\LaravelSetable\Commands;


use Illuminate\Console\Command;
use Isofman\LaravelSetable\Setting;

class ListSettings extends Command
{
    protected $signature = 'setable:list';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $commands = Setting::all()->map(function($item) {
            return [
                $item->opt_key,
                $item->opt_type,
                $item->opt_value,
            ];
        });
        $headers = ['Key', 'Type', 'Value'];
        $this->table($headers, $commands);
    }
}