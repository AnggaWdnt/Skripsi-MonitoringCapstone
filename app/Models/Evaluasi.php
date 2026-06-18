<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluasi extends Model
{
    protected $table = 'evaluasis';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'nilai_pkl',
        'catatan',
    ];

    /**
     * Relationship to the Student (User model).
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Relationship to the Lecturer/Advisor (User model).
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
