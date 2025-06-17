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

// app/Models/MataKuliah.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'user_id',
        'nama',
        'deskripsi',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'mata_kuliah_id');
    }
}

// app/Models/Kegiatan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'user_id',
        'mata_kuliah_id',
        'nama',
        'deskripsi',
        'jenis',
        'deadline',
        'prioritas',
        'estimasi_jam',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Enums
    public const JENIS_OPTIONS = [
        'tugas' => 'Tugas',
        'proyek' => 'Proyek',
        'kuis' => 'Kuis',
        'presentasi' => 'Presentasi',
        'nyatet materi' => 'Nyatet Materi',
        'UTS' => 'UTS',
        'UAS' => 'UAS',
    ];

    public const PRIORITAS_OPTIONS = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
    ];

    public const STATUS_OPTIONS = [
        'Belum Dimulai' => 'Belum Dimulai',
        'Sedang Berjalan' => 'Sedang Berjalan',
        'Selesai' => 'Selesai',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kegiatan_id');
    }

    public function progres()
    {
        return $this->hasMany(Progres::class, 'kegiatan_id');
    }

    public function riwayatKegiatan()
    {
        return $this->hasMany(RiwayatKegiatan::class, 'kegiatan_id');
    }

    // Scopes
    public function scopeUrgent($query)
    {
        return $query->where('deadline', '>=', now())
                    ->where('deadline', '<=', now()->addDays(7))
                    ->where('status', '!=', 'Selesai')
                    ->orderBy('deadline');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}

// app/Models/Jadwal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}

// app/Models/Progres.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;

    protected $table = 'progres';

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'tanggal',
        'durasi_belajar',
        'status_sebelum',
        'status_sesudah',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}

// app/Models/RiwayatKegiatan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKegiatan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kegiatan';

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'tanggal_selesai',
        'durasi_pengerjaan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}

// app/Models/ProfilPengguna.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPengguna extends Model
{
    use HasFactory;

    protected $table = 'profil_pengguna';

    protected $fillable = [
        'user_id',
        'nim',
        'jurusan',
        'fakultas',
        'foto_profil',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}