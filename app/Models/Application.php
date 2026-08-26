<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[ScopedBy([TenantScope::class])]
class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_no',
        'application_type',
        'vacancy_id',
        'school_id',
        'preferred_position',
        'preferred_department',
        'first_name',
        'last_name',
        'email',
        'phone',
        'whatsapp_number',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'pin_code',
        'highest_qualification',
        'experience_years',
        'current_employer',
        'current_salary',
        'expected_salary',
        'notice_period',
        'skills',
        'languages',
        'resume_path',
        'photo_path',
        'cover_letter',
        'portfolio_url',
        'linkedin_url',
        'declaration_accepted',
        'status',
        'admin_notes',
        'is_bookmarked',
        'applied_at',
        'transferred_from_school_id',
        'recommendation_notes',
        'transferred_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'declaration_accepted' => 'boolean',
        'is_bookmarked' => 'boolean',
        'applied_at' => 'datetime',
        'transferred_at' => 'datetime',
    ];

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) return null;
        return (int) $this->date_of_birth->diffInYears(now());
    }

    public function isGeneral(): bool
    {
        return $this->application_type === 'general' || empty($this->vacancy_id);
    }

    public function getDisplayPositionAttribute(): string
    {
        if ($this->vacancy_id && $this->vacancy) {
            return $this->vacancy->title;
        }
        return $this->preferred_position ?: 'General Application';
    }

    public function scopeGeneral($query)
    {
        return $query->where('application_type', 'general')->orWhereNull('vacancy_id');
    }

    public function scopeVacancy($query)
    {
        return $query->where('application_type', 'vacancy')->whereNotNull('vacancy_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (empty($application->reference_no)) {
                $prefix = ($application->application_type === 'general' || empty($application->vacancy_id)) ? 'GEN-' : 'APP-';
                $application->reference_no = $prefix . strtoupper(Str::random(8));
            }
        });
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function transferredFromSchool(): BelongsTo
    {
        return $this->belongsTo(School::class, 'transferred_from_school_id');
    }

    public function isTransferred(): bool
    {
        return !is_null($this->transferred_from_school_id);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getResumeUrlAttribute(): ?string
    {
        return $this->resume_path ? asset('media/' . $this->resume_path) : null;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo_path) {
            return asset('media/' . $this->photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=4F46E5&background=EEF2FF';
    }
}
