<?php

namespace App\Models;

use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "title"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->hasMany(Post::class, "category_id", "id");
    }
}
