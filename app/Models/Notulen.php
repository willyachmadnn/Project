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
    protected $table = 'notulens';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agenda_id',
        'pembuat',
        'isi_notulen',
        'pimpinan_rapat_ttd',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Agenda.
     * Setiap notulen dimiliki oleh satu agenda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agenda(): BelongsTo
    {
        // Menghubungkan model Notulen ke model Agenda
        // Foreign key di tabel ini adalah 'agenda_id'
        // Primary key di tabel agendas adalah 'agenda_id'
        return $this->belongsTo(Agenda::class, 'agenda_id', 'agenda_id');
    }
}
