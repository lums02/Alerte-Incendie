<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorReading extends Model
{
    protected $fillable = [
        'sensor_id',
        'value',
        'unit',
        'measured_at',
        'raw_data',
        'quality'
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'measured_at' => 'datetime',
        'raw_data' => 'array'
    ];

    /**
     * Relation avec le capteur
     */
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }

    /**
     * Scope pour les lectures récentes
     */
    public function scopeRecent($query, int $minutes = 60)
    {
        return $query->where('measured_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Scope pour les lectures par qualité
     */
    public function scopeGoodQuality($query)
    {
        return $query->where('quality', 'good');
    }

    /**
     * Obtenir la couleur selon la qualité
     */
    public function getQualityColorAttribute(): string
    {
        return match($this->quality) {
            'good' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            default => 'gray'
        };
    }

    /**
     * Formater la valeur avec son unité
     */
    public function getFormattedValueAttribute(): string
    {
        return $this->value . ($this->unit ? ' ' . $this->unit : '');
    }
}
