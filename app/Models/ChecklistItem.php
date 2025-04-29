<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    protected $fillable = ['name', 'checklist_id', 'is_checked'];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }
}
