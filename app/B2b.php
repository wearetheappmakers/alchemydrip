<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class B2b extends Model
{
    protected $table = 'b2b';
    protected $primarykey = 'id';
    protected $guarded = ['id'];
    protected $appends = ['status_full_name'];

    public function getStatusFullNameAttribute()
    {
        if($this->status == 1){
            return 'Completed';
        }
        else
        {
        	return 'Pending';
        }
    }

    public function edit_clone()
    {
        return $this->hasMany(B2b_child::class,'b2b_id','id');
    }
    public function users()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
