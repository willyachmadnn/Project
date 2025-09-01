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
     * Menentukan primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_tamu';

    /**
     * Menunjukkan bahwa primary key adalah auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Menentukan tipe data dari primary key.
     *
     * @var string
     */
    protected $keyType = 'int';
    
    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'NIP',
        'nama_tamu',
        'instansi',
        'jk',
        'agenda_id',
    ];
    
    /**
     * Mendefinisikan relasi "belongsTo" ke model Agenda.
     * Setiap tamu terhubung ke satu agenda.
     */
    public function agenda(): BelongsTo
    {
        // Menghubungkan model Tamu dengan model Agenda
        // Foreign key di tabel ini adalah 'agenda_id'
        // Primary key di tabel agendas adalah 'agenda_id'
        return $this->belongsTo(Agenda::class, 'agenda_id', 'agenda_id');
    }

    /**
     * Mendefinisikan relasi "belongsTo" ke model Opd.
     * Setiap tamu terhubung ke satu OPD melalui kolom instansi.
     */
    public function opd(): BelongsTo
    {
        // Menghubungkan model Tamu dengan model Opd
        // Foreign key di tabel ini adalah 'instansi'
        // Primary key di tabel opd adalah 'opd_id'
        return $this->belongsTo(Opd::class, 'instansi', 'opd_id');
    }
}
