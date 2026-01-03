<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id', 'author_name', 'rating', 'content', 'approved'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
