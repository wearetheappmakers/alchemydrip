<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rawmaterial extends Model
{
    protected $table = 'raw_material';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

}
