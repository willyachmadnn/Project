<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;
    
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Clear cache when agenda is created, updated, or deleted
        static::saved(function () {
            self::clearAgendaCache();
        });
        
        static::deleted(function () {
            self::clearAgendaCache();
        });
    }

    /**
     * PERBAIKAN: Menggunakan nama tabel 'agendas' (plural)
     */
    protected $table = 'agendas';
    protected $primaryKey = 'agenda_id';

    protected $fillable = [
        'nama_agenda',
        'tempat',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'dihadiri',
        'admin_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // --- QUERY SCOPES ---

    public function scopeMenunggu(Builder $query): void
    {
        $now = Carbon::now('Asia/Jakarta')->toDateTimeString();
        $query->whereRaw("datetime(date(tanggal) || ' ' || jam_mulai) > ?", [$now]);
    }

    public function scopeBerlangsung(Builder $query): void
    {
        $now = Carbon::now('Asia/Jakarta')->toDateTimeString();
        $query->whereRaw("datetime(date(tanggal) || ' ' || jam_mulai) <= ?", [$now])
            ->whereRaw("
                CASE
                    WHEN time(jam_mulai) <= time(jam_selesai)
                    THEN datetime(date(tanggal) || ' ' || jam_selesai)
                    ELSE datetime(date(tanggal, '+1 day') || ' ' || jam_selesai)
                END >= ?
              ", [$now]);
    }

    public function scopeBerakhir(Builder $query): void
    {
        $now = Carbon::now('Asia/Jakarta')->toDateTimeString();
        $query->whereRaw("
            CASE
                WHEN time(jam_mulai) <= time(jam_selesai)
                THEN datetime(date(tanggal) || ' ' || jam_selesai)
                ELSE datetime(date(tanggal, '+1 day') || ' ' || jam_selesai)
            END < ?
        ", [$now]);
    }

    // --- ACCESSOR ---

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                $tz = 'Asia/Jakarta';
                $now = Carbon::now($tz);
                $start = Carbon::parse($this->tanggal->toDateString() . ' ' . $this->jam_mulai, $tz);
                $end = Carbon::parse($this->tanggal->toDateString() . ' ' . $this->jam_selesai, $tz);

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

    public function admin()
    {
        // PERBAIKAN: Relasi seharusnya ke model Admin, bukan User
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id', 'admin_id');
    }

    public function tamu(): HasMany
    {
        return $this->hasMany(Tamu::class, 'agenda_id', 'agenda_id');
    }

    public function notulen(): HasOne
    {
        return $this->hasOne(Notulen::class, 'agenda_id', 'agenda_id');
    }
    
    /**
     * Clear agenda-related cache
     */
    public static function clearAgendaCache(): void
    {
        Cache::forget('agenda_counts_menunggu');
        Cache::forget('agenda_counts_berlangsung');
        Cache::forget('agenda_counts_berakhir');
        
        // Clear cache with different prefixes if any
        $prefix = config('cache.prefix');
        if ($prefix) {
            Cache::forget($prefix . 'agenda_counts_menunggu');
            Cache::forget($prefix . 'agenda_counts_berlangsung');
            Cache::forget($prefix . 'agenda_counts_berakhir');
        }
    }
}