<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Concerns\TracksFieldChanges;

#[Fillable(['title', 'slug', 'description', 'content', 'thumbnail_id', 'status'])]
class Activity extends Model
{
    use SoftDeletes, HasUuids, TracksFieldChanges;

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class)->orderBy('starts_at');
    }
}
