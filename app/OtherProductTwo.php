<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherProductTwo extends Model
{
    protected $table = 'other_product_two';
    protected $primarykey = 'id';
    protected $guarded = ['id'];
}
