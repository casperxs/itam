<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingCriterion extends Model
{
    protected $fillable = [
        'name',
        'label',
        'weight_percentage',
        'options',
        'auto_calculated',
        'active',
    ];

    protected $casts = [
        'options' => 'array',
        'auto_calculated' => 'boolean',
        'active' => 'boolean',
    ];

    public static function getActiveByName($name)
    {
        return static::where('name', $name)->where('active', true)->first();
    }

    public static function getAllActive()
    {
        return static::where('active', true)
                    ->orderBy('weight_percentage', 'desc')
                    ->get();
    }
    
    public static function getActiveWithOptions()
    {
        return static::where('active', true)
                    ->orderBy('weight_percentage', 'desc')
                    ->get()
                    ->map(function($criterion) {
                        $criterion->options = is_string($criterion->options) 
                            ? json_decode($criterion->options, true) 
                            : $criterion->options;
                        return $criterion;
                    });
    }
}
