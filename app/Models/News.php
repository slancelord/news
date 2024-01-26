<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;

class News extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function tags() 
    {
        return $this->belongsToMany(Tag::class)->using(NewsTag::class);
    }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }
}
