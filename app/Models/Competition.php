<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'slug', 'description', 'content', 'thumbnail_id', 'status'])]
class Competition extends Model
{
    use SoftDeletes, HasUuids;

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function submissions()
    {
        return $this->hasMany(CompetitionSubmission::class);
    }

    public function winner()
    {
        return $this->hasOne(CompetitionSubmission::class)->where('is_winner', true);
    }
}
