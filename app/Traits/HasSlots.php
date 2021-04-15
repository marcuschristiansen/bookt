<?php

namespace App\Traits;

use App\Models\Slot;
use PhpParser\Node\Expr\Array_;

trait HasSlots
{
    public function getSlotById($id)
    {
        return $this->slots()->first(function($value, $key) use ($id) {
            return $value->id === $id;
        });
    }
}
