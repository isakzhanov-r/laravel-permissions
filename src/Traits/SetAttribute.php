<?php

namespace IsakzhanovR\UserPermission\Traits;

use Illuminate\Support\Str;

use function trim;

trait SetAttribute
{
    protected function setTitleAttribute($value)
    {
        $this->setManualAttribute('title', trim($value));

        $this->setSlugAttribute($value);
    }

    protected function setSlugAttribute($value)
    {
        $this->setManualAttribute('slug', Str::slug(trim($value)));
    }

    public function setManualAttribute($key, $value, $default = null)
    {
        $this->attributes[$key] = $value ?: $default;
    }
}
