<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = false;

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            if (!Schema::hasTable('settings')) {
                return $default;
            }
            $value = static::where('key', $key)->value('value');
            return $value !== null ? $value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, ?string $value): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
