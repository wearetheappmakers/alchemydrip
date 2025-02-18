<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_statuses';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

	// protected static function boot()
	// {
	// 	parent::boot();
 //        static::addGlobalScope(new DeleteScope);
 //        static::addGlobalScope(new OrderScope);

 //        static::created(function($model)
 //        {
 //            $model->order = $model->id;
 //            $model->save();
 //        });

 //    }

}

