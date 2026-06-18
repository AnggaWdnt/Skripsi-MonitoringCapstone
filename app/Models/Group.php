<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Group extends Model
{
    protected $fillable = [
        'group_name',
        'theme',
        'title',
        'description',
        'survey_file',
        'dosen_id',
        'status',
        'laporan_file',
        'nilai_capstone',
        'catatan',
    ];

    /**
     * Get the student members of the group.
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'group_id');
    }

    /**
     * Get the lecturer supervisor of the group.
     */
    public function pembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
