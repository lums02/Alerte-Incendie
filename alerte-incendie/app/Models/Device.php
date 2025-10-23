<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $fillable = [
        'name',
        'location',
        'api_key',
        'status',
        'last_seen_at',
        'settings'
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'settings' => 'array'
    ];

    /**
     * Relation avec les capteurs
     */
    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class);
    }

    /**
     * Relation avec les alertes
     */
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Générer une clé API unique
     */
    public static function generateApiKey(): string
    {
        return 'device_' . bin2hex(random_bytes(16));
    }

    /**
     * Vérifier si le device est en ligne
     */
    public function isOnline(): bool
    {
        return $this->status === 'online' && 
               $this->last_seen_at && 
               $this->last_seen_at->diffInMinutes(now()) < 5;
    }
}
