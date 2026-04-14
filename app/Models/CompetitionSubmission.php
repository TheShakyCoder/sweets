<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['competition_id', 'user_id', 'name', 'description', 'media_id', 'is_winner'])]
class CompetitionSubmission extends Model
{
    use SoftDeletes, HasUuids;

    protected $casts = [
        'is_winner' => 'boolean',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
