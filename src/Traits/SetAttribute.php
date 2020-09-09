<?php

namespace IsakzhanovR\UserPermission\Traits;

use Illuminate\Support\Str;
use function trim;

/**
 * Trait SetAttribute
 *
 * @package IsakzhanovR\UserPermission\Traits
 */
trait SetAttribute
{
    /**
     * @param $key
     * @param $value
     * @param null $default
     */
    public function setManualAttribute($key, $value, $default = null)
    {
        $this->attributes[$key] = $value ?: $default;
    }

    /**
     * @param $value
     */
    protected function setTitleAttribute($value)
    {
        $this->setManualAttribute('title', trim($value));

        $this->setSlugAttribute($value);
    }

    /**
     * @param $value
     */
    protected function setSlugAttribute($value)
    {
        $this->setManualAttribute('slug', Str::slug(trim($value)));
    }
}
