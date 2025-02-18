<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Po extends Model
{
    protected $table = 'po';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

     public function edit_clone()
    {
        return $this->hasMany(Po_child::class,'po_id','id');
    }
}

