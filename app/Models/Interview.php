<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([TenantScope::class])]
class Interview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'school_id',
        'scheduled_date',
        'scheduled_time',
        'location_type',
        'location_address_or_link',
        'panel_members',
        'remarks',
        'status',
        'feedback',
        'score',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
