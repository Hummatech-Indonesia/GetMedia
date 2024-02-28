<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'name', 'photo', 'content', 'sinopsis', 'is_primary', 'sub_category_id', 'slug', 'status', 'views'];
    protected $table = 'news';

    public $incrementing = false;
    public $keyType = 'char';

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
