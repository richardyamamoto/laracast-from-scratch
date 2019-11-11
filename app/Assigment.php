<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assigment extends Model
{
    public function complete() {
        $this->completed = true;
        $this->save();
    }
}
