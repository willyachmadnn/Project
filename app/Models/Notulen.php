<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notulen extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'notulen';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agenda_id',
        'isi_notulen',
        'pembuat',
    ];
    
    /**
     * Get the agenda that owns the notulen.
     */
    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }
}
