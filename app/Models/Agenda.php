<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agenda extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_agenda',
        'tempat',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];
    
    /**
     * Get the tamu for the agenda.
     */
    public function tamu(): HasMany
    {
        return $this->hasMany(Tamu::class);
    }
    
    /**
     * Get the notulen for the agenda.
     */
    public function notulen(): HasOne
    {
        return $this->hasOne(Notulen::class);
    }
}
