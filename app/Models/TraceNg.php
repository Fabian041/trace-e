<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraceNg extends Model
{
    use HasFactory;

    protected $table = 'trace_ngs';

    protected $guarded = ['id'];
}
