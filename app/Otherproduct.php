<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otherproduct extends Model
{
    protected $table = 'other_product';
    protected $primarykey = 'id';
    protected $guarded = ['id'];
}
