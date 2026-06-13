<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'department_id',
        'status',
        'priority',
        'notes',
        'approved_by',
        'approved_at',
        'delivered_at',
    ];

    protected $casts = [
        'approved_at'  => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // ─── Helpers ──────────────────────────
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    // ─── Relaciones ───────────────────────
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class, 'request_id');
    }
}