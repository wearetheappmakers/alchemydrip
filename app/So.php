<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class So extends Model
{
    protected $table = 'so';
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


    public function childData()
    {
        return $this->hasMany(So_child::class,'so_id','id');
    }
    public function otherData()
    {
        return $this->hasMany(So_other::class,'so_id','id');
    }
    public function sorawData()
    {
        return $this->hasMany(SoRaw::class,'so_id','id');
    }
    public function users()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function otherDataTwo()
    {
        return $this->hasMany(So_OtherTwo::class,'so_id','id');
    }
    public function otherDataThree()
    {
        return $this->hasMany(So_OtherThree::class,'so_id','id');
    }
}
