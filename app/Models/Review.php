<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTime;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['rating', 'comment'];

    public static function boot()
    {
        parent::boot();

        static::saved(function (Review $review) {
            $review->product->average_rating = $review->product->averageRating();
            $review->product->save();
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value): string
    {
        $dateTime = new DateTime($value);
        return $dateTime->format('d/m/Y');
    }
}
