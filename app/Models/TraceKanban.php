<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraceKanban extends Model
{
    use HasFactory;

    protected $table = 'trace_kanbans';

    protected $guarded = ['id'];
}
