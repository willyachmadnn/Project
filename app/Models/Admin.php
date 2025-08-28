<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // PERUBAHAN: Gunakan Authenticatable
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// PERUBAHAN: extends Authenticatable
class Admin extends Authenticatable
{
    use HasFactory;

    /** Tabel & primary key */
    protected $table = 'admins';
    protected $primaryKey = 'admin_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /** Kolom yang boleh di-*mass assign* */
    protected $fillable = [
        'admin_id',
        'nama_admin',
        'opd_admin',
        'password',
    ];

    /** Sembunyikan password saat toArray()/JSON */
    protected $hidden = [
        'password',
        'remember_token', // Tambahkan remember_token
    ];

    /** Relasi: satu admin punya banyak agenda */
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'admin_id', 'admin_id');
    }

    /** Relasi: admin belongs to OPD */
    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_admin', 'opd_id');
    }

    /**
     * Mutator: kalau di-set pakai plaintext, otomatis di-hash.
     */
    public function setPasswordAttribute($value): void
    {
        if (is_string($value) && !Str::startsWith($value, ['$2y$', '$argon2id$'])) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }
}