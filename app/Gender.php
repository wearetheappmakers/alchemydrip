<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = 'gender';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

    public function genders()
    {
        return $this->belongsToMany(Product::class, 'product_gender');
    }
}

