<?php


namespace Isofman\LaravelSetable;


use Illuminate\Support\Arr;

class SettingHandler
{
    public function set(string $key, $value, $type = null, $force = false)
    {
        $type = $type ?? 'string';

        $item = Setting::firstOrNew(['opt_key' => $key]);

        if(! $item->exists && ! $force) {
            return '__not_exists';
        }

        $item->fill([
            'opt_value' => $value,
            'opt_type' => $type
        ])->save();

        app('cache')->set('setable.' . $key, $value);

        return $this->format($value, $type);
    }

    public function get($keys, $default = null)
    {
        if(is_array($keys) && $items = Setting::whereIn('opt_key', Arr::wrap($keys))->get()) {
            return $items->mapWithKeys(function ($item) {
                return [$item->opt_key => $this->format($item->opt_value, $item->opt_type)];
            })->toArray();
        }

        if($item = Setting::where('opt_key', $keys)->first()) {
            return $this->format($item->opt_value, $item->opt_type);
        }

        return $this->format($default, 'string');
    }

    protected function format($value, $type)
    {
        $formatter = config('setable.formatters.' . $type);

        if($formatter) {
            if(is_array($formatter) && list($func, $args) = $formatter) {
                $args = $args ? $args : [];
                return call_user_func_array($func, Arr::prepend($args, $value));
            }
            return call_user_func($formatter, $value);
        }

        return $value;
    }
}
