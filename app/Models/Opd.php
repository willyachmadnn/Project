<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opd extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opd';
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'opd_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'opd_id',
        'nama_opd',
    ];
    
    /**
     * Get the admins for the OPD.
     */
    public function admins()
    {
        return $this->hasMany(\App\Models\Admin::class, 'opd_admin', 'opd_id');
    }
    
    /**
     * Get the pegawais for the OPD.
     */
    public function pegawais()
    {
        return $this->hasMany(\App\Models\Pegawai::class, 'instansi', 'opd_id');
    }
    
    /**
     * Get the tamus for the OPD.
     */
    public function tamus()
    {
        return $this->hasMany(\App\Models\Tamu::class, 'instansi', 'opd_id');
    }
}
