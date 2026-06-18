<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'nidn',
        'prodi',
        'angkatan',
        'status_pkl',
        'dosen_id',
        'company_name',
        'company_address',
        'pkl_title',
        'pkl_start_date',
        'pkl_end_date',
        'laporan_file',
        'bukti_pkl',
        'group_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'pkl_start_date' => 'date',
            'pkl_end_date' => 'date',
        ];
    }

    /**
     * Get student's advisor/dosen (for Mahasiswa).
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Get lecturer's students (for Dosen).
     */
    public function mahasiswas(): HasMany
    {
        return $this->hasMany(User::class, 'dosen_id');
    }

    /**
     * Get student's daily logbooks (for Mahasiswa).
     */
    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class, 'mahasiswa_id');
    }

    /**
     * Get student's evaluations (for Mahasiswa).
     */
    public function evaluasi(): HasOne
    {
        return $this->hasOne(Evaluasi::class, 'mahasiswa_id');
    }

    /**
     * Get the capstone group of the student.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
