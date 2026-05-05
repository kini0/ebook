<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            if (! $row) {
                return $default;
            }
            return match ($row->type) {
                'integer' => (int) $row->value,
                'boolean' => filter_var($row->value, FILTER_VALIDATE_BOOLEAN),
                'json'    => json_decode((string) $row->value, true),
                default   => $row->value,
            };
        });
    }

    public static function put(string $key, mixed $value, string $group = 'general', string $type = 'string'): self
    {
        $row = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_scalar($value) ? (string) $value : json_encode($value),
                'group' => $group,
                'type'  => $type,
            ]
        );
        Cache::forget("setting:{$key}");
        return $row;
    }
}
