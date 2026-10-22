<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HowItWorksStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_number',
        'title',
        'description',
        'image',
        'icon',
        'button_text',
        'button_url',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
