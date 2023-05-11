<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanMaster extends Model
{
    use HasFactory;

    protected $table = 'trace_kanban_masters';

    protected $guarded = ['id'];
}
