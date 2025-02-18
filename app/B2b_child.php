<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class B2b_child extends Model
{
    protected $table = 'b2b_child';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

}
