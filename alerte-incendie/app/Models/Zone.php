<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    protected $fillable = [
        'name',
        'description',
        'pos_x',
        'pos_y',
        'pos_z',
        'dimensions'
    ];

    protected $casts = [
        'pos_x' => 'decimal:2',
        'pos_y' => 'decimal:2',
        'pos_z' => 'decimal:2',
        'dimensions' => 'array'
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
     * Obtenir la position sous forme de tableau
     */
    public function getPositionAttribute(): array
    {
        return [
            'x' => $this->pos_x,
            'y' => $this->pos_y,
            'z' => $this->pos_z
        ];
    }
}
