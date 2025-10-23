<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    protected $fillable = [
        'device_id',
        'sensor_id',
        'zone_id',
        'level',
        'title',
        'message',
        'data',
        'triggered_at',
        'resolved_at',
        'status'
    ];

    protected $casts = [
        'data' => 'array',
        'triggered_at' => 'datetime',
        'resolved_at' => 'datetime'
    ];

    /**
     * Relation avec le device
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Relation avec le capteur
     */
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }

    /**
     * Relation avec la zone
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Résoudre une alerte
     */
    public function resolve(): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now()
        ]);
    }

    /**
     * Obtenir la couleur selon le niveau
     */
    public function getColorAttribute(): string
    {
        return match($this->level) {
            'info' => 'blue',
            'warning' => 'yellow',
            'critical' => 'orange',
            'emergency' => 'red',
            default => 'gray'
        };
    }

    /**
     * Obtenir l'icône selon le niveau
     */
    public function getIconAttribute(): string
    {
        return match($this->level) {
            'info' => 'info-circle',
            'warning' => 'exclamation-triangle',
            'critical' => 'exclamation-circle',
            'emergency' => 'fire',
            default => 'question-circle'
        };
    }
}
