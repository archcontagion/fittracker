<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public $timestamps = false;


    public function __toString() {
        return $this->name;
    }
}
