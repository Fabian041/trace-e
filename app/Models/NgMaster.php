<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NgMaster extends Model
{
    use HasFactory;

    protected $table = 'trace_ng_masters';

    protected $guarded = ['id'];
}
