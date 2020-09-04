<?php

namespace IsakzhanovR\UserPermission\Traits;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config as IlluminateConfig;
use Illuminate\Support\Str;
use IsakzhanovR\UserPermission\Helpers\Config;

/** @mixin \Illuminate\Database\Eloquent\Model */
trait Cacheable
{
    protected function cache(string $prefix, Closure $callback, $values)
    {
        if (Config::isCache()) {
            $key = $this->cacheKey($prefix, $values);
dump($key);
            return Cache::remember($key, IlluminateConfig::get('cache.ttl'), $callback);
        }

        return $callback();
    }

    protected function cacheKey(string $prefix, $values): string
    {
        $values = Arr::flatten(Arr::wrap($values));

        return Str::slug(
            $prefix . '-' . $this->cacheUserKey() . '-' . implode('-', $values)
        );
    }

    protected function cacheUserKey(): string
    {
        return $this->getAttribute(
            $this->getKeyName()
        );
    }
}
