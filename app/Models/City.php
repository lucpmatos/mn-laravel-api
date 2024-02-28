<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'short_name',
        'created_at',
        'updated_at',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($state){
            $state->addresses()->delete();
        });
    }

    /** City belongs to State
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /** City has many Addresses
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
