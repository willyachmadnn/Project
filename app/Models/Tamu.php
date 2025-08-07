<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tamu extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'tamu';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agenda_id',
        'nama',
        'instansi',
        'jabatan',
        'email',
        'telepon',
        'kehadiran',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'kehadiran' => 'boolean',
    ];
    
    /**
     * Get the agenda that owns the tamu.
     */
    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }
}
