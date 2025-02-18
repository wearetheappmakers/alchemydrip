<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'school';
    protected $primarykey = 'id';
    protected $guarded = ['id'];

    public function product() {
        return $this->hasMany(Product::class, 'school_id', 'id');
    }
}
