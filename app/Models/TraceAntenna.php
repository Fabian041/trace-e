<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraceAntenna extends Model
{
    use HasFactory;

    protected $table = 'trace_antennas';

    protected $guarded = ['id'];
}
