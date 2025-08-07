<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

/**
 * Model Agenda
 *
 * Model ini merepresentasikan sebuah agenda dalam aplikasi.
 * Ini adalah versi gabungan yang mencakup semua fungsionalitas
 * yang diperlukan oleh halaman depan (landing page) dan panel admin (CRUD).
 */
class Agenda extends Model
{
    use HasFactory;

    /**
     * Nama tabel database yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'agenda';

    /**
     * Nama primary key dari tabel.
     *
     * @var string
     */
    protected $primaryKey = 'agenda_id';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Ini adalah gabungan dari kedua file model sebelumnya.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_agenda',
        'tempat',
        'tanggal',
        'jam_mulai',
        'jam_selesai', // Menggunakan 'jam_selesai' sebagai nama kolom standar
        'dihadiri',
        'admin_id',
    ];

    /**
     * Tipe data asli dari atribut harus di-casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    // --- QUERY SCOPES ---
    // Scopes memungkinkan kita untuk membuat query yang dapat digunakan kembali.

    /**
     * Scope untuk memfilter agenda yang statusnya "Menunggu".
     * Agenda dianggap "Menunggu" jika waktu mulainya di masa depan.
     */
    public function scopeMenunggu(Builder $query): void
    {
        $now = Carbon::now('Asia/Jakarta')->toDateTimeString();
        $query->whereRaw("datetime(tanggal || ' ' || jam_mulai) > ?", [$now]);
    }

    /**
     * Scope untuk memfilter agenda yang statusnya "Berlangsung".
     * Agenda dianggap "Berlangsung" jika waktu saat ini berada di antara waktu mulai dan berakhirnya.
     */
    public function scopeBerlangsung(Builder $query): void
    {
        $now = Carbon::now('Asia/Jakarta')->toDateTimeString();
        $query->whereRaw("datetime(tanggal || ' ' || jam_mulai) <= ?", [$now])
            ->whereRaw("
                CASE
                    WHEN time(jam_mulai) <= time(jam_selesai) THEN datetime(tanggal || ' ' || jam_selesai)
                    ELSE datetime(tanggal, '+1 day', 'start of day') + time(jam_selesai)
                END >= ?
              ", [$now]);
    }

    /**
     * Scope untuk memfilter agenda yang statusnya "Selesai".
     * Agenda dianggap "Selesai" jika waktu berakhirnya sudah lewat.
     */
    public function scopeSelesai(Builder $query): void
    {
        $now = Carbon::now('Asia/Jakarta')->toDateTimeString();
        $query->whereRaw("
            CASE
                WHEN time(jam_mulai) <= time(jam_selesai) THEN datetime(tanggal || ' ' || jam_selesai)
                ELSE datetime(tanggal, '+1 day', 'start of day') + time(jam_selesai)
            END < ?
        ", [$now]);
    }

    // --- ACCESSOR ---
    // Accessor memungkinkan kita membuat atribut virtual pada model.

    /**
     * Accessor untuk mendapatkan status agenda ('Menunggu', 'Berlangsung', 'Selesai').
     * Dapat diakses melalui properti virtual `$agenda->status`.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                $tz = 'Asia/Jakarta';
                $now = Carbon::now($tz);

                // Membuat objek Carbon dari tanggal dan jam mulai
                $start = Carbon::parse($this->tanggal->toDateString() . ' ' . $this->jam_mulai, $tz);

                // Membuat objek Carbon dari tanggal dan jam selesai
                $end = Carbon::parse($this->tanggal->toDateString() . ' ' . $this->jam_selesai, $tz);

                // Menangani acara yang berlangsung melewati tengah malam
                if ($end->isBefore($start)) {
                    $end->addDay();
                }

                if ($now->isBefore($start))
                    return 'Menunggu';
                if ($now->between($start, $end))
                    return 'Berlangsung';
                return 'Selesai';
            },
        );
    }

    // --- RELATIONS ---
    // Mendefinisikan hubungan antar model.

    /**
     * Mendefinisikan relasi "belongsTo" ke model User (admin yang membuat agenda).
     */
    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id', 'id');
    }

    /**
     * Mendefinisikan relasi "hasMany" ke model Tamu.
     * Satu agenda bisa memiliki banyak tamu.
     */
    public function tamu(): HasMany
    {
        // Menentukan foreign key dan local key secara eksplisit untuk kejelasan
        return $this->hasMany(Tamu::class, 'agenda_id', 'agenda_id');
    }

    /**
     * Mendefinisikan relasi "hasOne" ke model Notulen.
     * Satu agenda hanya memiliki satu notulen.
     */
    public function notulen(): HasOne
    {
        // Menentukan foreign key dan local key secara eksplisit untuk kejelasan
        return $this->hasOne(Notulen::class, 'agenda_id', 'agenda_id');
    }
}