<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'date',
        'activity',
        'documentation',
        'status',
        'dosen_note',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relationship to the Student (User model).
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
