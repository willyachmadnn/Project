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
use App\Models\Admin;

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
        // Hapus cache untuk semua admin
        $admins = Admin::all(['admin_id']);
        
        foreach ($admins as $admin) {
            $adminId = $admin->admin_id;
            
            // Hapus cache untuk dashboard
            Cache::forget("agenda_pending_count_{$adminId}");
            Cache::forget("agenda_ongoing_count_{$adminId}");
            Cache::forget("agenda_finished_count_{$adminId}");
            
            // Hapus cache untuk landing page dengan berbagai timeRange
            foreach (['1', '2', '3', '4', '5'] as $timeRange) {
                Cache::forget("landing_counts_{$timeRange}_{$adminId}");
            }
        }
        
        // Hapus cache untuk guest
        foreach (['1', '2', '3', '4', '5'] as $timeRange) {
            Cache::forget("landing_counts_{$timeRange}_guest");
        }
    }
}