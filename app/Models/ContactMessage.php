<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'reference_no',
        'message',
        'status',
        'ip_address',
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && in_array($status, ['unread', 'read', 'replied', 'archived'])) {
            return $query->where('status', $status);
        }
        return $query;
    }
}
