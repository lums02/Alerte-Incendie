<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    protected $fillable = [
        'device_id',
        'zone_id',
        'name',
        'type',
        'unit',
        'threshold_warn',
        'threshold_alarm',
        'status',
        'pos_x',
        'pos_y',
        'pos_z',
        'calibration_data'
    ];

    protected $casts = [
        'threshold_warn' => 'decimal:2',
        'threshold_alarm' => 'decimal:2',
        'pos_x' => 'decimal:2',
        'pos_y' => 'decimal:2',
        'pos_z' => 'decimal:2',
        'calibration_data' => 'array'
    ];

    /**
     * Relation avec le device
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Relation avec la zone
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Relation avec les lectures
     */
    public function readings(): HasMany
    {
        return $this->hasMany(SensorReading::class);
    }

    /**
     * Relation avec les alertes
     */
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Obtenir la dernière lecture
     */
    public function latestReading()
    {
        return $this->hasOne(SensorReading::class)->latest('measured_at');
    }

    /**
     * Vérifier si une valeur déclenche une alerte
     */
    public function checkAlert(float $value): ?string
    {
        if ($this->threshold_alarm && $value >= $this->threshold_alarm) {
            return 'critical';
        }
        
        if ($this->threshold_warn && $value >= $this->threshold_warn) {
            return 'warning';
        }
        
        return null;
    }

    /**
     * Obtenir le statut de couleur pour l'interface
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            'calibrating' => 'yellow',
            'error' => 'red',
            default => 'gray'
        };
    }
}
