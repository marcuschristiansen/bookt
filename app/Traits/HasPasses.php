<?php

namespace App\Traits;

use App\Models\Pass;

trait HasPasses
{
    /**
     * Get the default created pass for this slot
     */
    public function getDefaultPass()
    {
        return $this->passes()->first();
    }
}
