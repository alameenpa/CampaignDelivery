<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type'
    ];


    public function campaigns() {
        return $this->belongsToMany(Campaign::class, 'template_id');
    }
}
