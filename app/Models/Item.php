<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit',
        'estimated_price',
        'active', 
    ];

    protected $casts = [
        'active'          => 'boolean',
        'estimated_price' => 'decimal:2',
    ];

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}