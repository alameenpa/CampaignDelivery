<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'type',
        'user_segment_ids',
        'template_id',
        'scheduled_at',
        'status',
        'delivery_status',
        'created_by',
        'timestamps'
    ];

    public function template() {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }
}
