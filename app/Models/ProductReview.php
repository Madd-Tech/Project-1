<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'customer_id',
        'reviewer_name',
        'reviewer_email',
        'rating',
        'comment',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'rating'      => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Censor the reviewer name: show first 3 chars then *** for the rest.
     * e.g. "Budi Santoso" → "Bud*********"
     */
    public function getCensoredNameAttribute(): string
    {
        $name = $this->reviewer_name ?? '';
        if (strlen($name) <= 3) return $name;
        return substr($name, 0, 3) . str_repeat('*', strlen($name) - 3);
    }
}
