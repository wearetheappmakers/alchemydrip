<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    
    protected $table = 'ledger';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
}
