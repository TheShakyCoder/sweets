<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'slug', 'description', 'content', 'thumbnail'])]
class Post extends Model
{
    use SoftDeletes, HasUuids;
}
