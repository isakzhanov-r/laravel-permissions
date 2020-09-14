<?php

namespace IsakzhanovR\Permissions\Helpers;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

final class Cacheable
{
    /**
     * @param  string  $prefix
     * @param  \Closure  $callback
     * @param $values
     *
     * @return mixed
     */
    public static function make(string $prefix, Closure $callback, $values)
    {
        if (Configable::isCache()) {
            $key = self::cacheKey($prefix, $values);

            return Cache::remember($key, Config::get('cache.ttl'), $callback);
        }

        return $callback();
    }

    /**
     * @param  mixed  ...$args
     *
     * @return string
     */
    public static function prefix(...$args): string
    {
        return implode('-', $args);
    }

    /**
     * @param  string  $prefix
     * @param $values
     *
     * @return string
     */
    protected static function cacheKey(string $prefix, $values): string
    {
        $values = Arr::flatten(Arr::wrap($values));

        return Str::slug(
            $prefix . '-' . implode('-', $values)
        );
    }
}
