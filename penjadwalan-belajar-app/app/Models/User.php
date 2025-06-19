<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    
    public function initials()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        foreach ($names as $n) {
            $initials .= strtoupper(substr($n, 0, 1));
        }
        return $initials;
    }

    // Relationships
    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function progres()
    {
        return $this->hasMany(Progres::class);
    }

    public function riwayatKegiatan()
    {
        return $this->hasMany(RiwayatKegiatan::class);
    }

    public function profilPengguna()
    {
        return $this->hasOne(ProfilPengguna::class);
    }
}

