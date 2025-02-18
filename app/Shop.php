<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shop';
    protected $primarykey = 'id';
    protected $guarded = ['id'];
}
