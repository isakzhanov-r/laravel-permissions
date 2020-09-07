<?php


namespace IsakzhanovR\UserPermission\Helpers;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config as IlluminateConfig;
use Illuminate\Support\Str;

class Cacheable
{
    public static function make(string $prefix, Closure $callback, $values)
    {
        if (Config::isCache()) {
            $key = self::cacheKey($prefix, $values);

            return Cache::remember($key, IlluminateConfig::get('cache.ttl'), $callback);
        }

        return $callback();
    }

    public static function prefix(...$args): string
    {
        return implode('-', $args);
    }

    protected static function cacheKey(string $prefix, $values): string
    {
        $values = Arr::flatten(Arr::wrap($values));

        return Str::slug(
            $prefix . '-' . implode('-', $values)
        );
    }
}
