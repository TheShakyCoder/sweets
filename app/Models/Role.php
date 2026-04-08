<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'slug'])]
class Role extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    public function users()
    {
        return $this->belongsToMany(User::class, 'privileges')->using(Privilege::class);
    }

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    public function rights()
    {
        return $this->hasMany(Right::class);
    }
}
