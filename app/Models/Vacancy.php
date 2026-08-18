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
class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'title',
        'slug',
        'vacancy_type',
        'department_id',
        'global_class_id',
        'job_category_id',
        'employment_type',
        'experience_level',
        'min_qualification',
        'salary_from',
        'salary_to',
        'salary_currency',
        'gender_preference',
        'number_of_vacancies',
        'location',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'deadline',
        'publish_date',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'seo_url',
        'view_count',
    ];

    protected $casts = [
        'deadline' => 'date',
        'publish_date' => 'date',
        'is_featured' => 'boolean',
        'salary_from' => 'decimal:2',
        'salary_to' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vacancy) {
            if (empty($vacancy->slug)) {
                $vacancy->slug = Str::slug($vacancy->title) . '-' . Str::random(5);
            }
            if (empty($vacancy->seo_url)) {
                $vacancy->seo_url = '/jobs/' . $vacancy->slug;
            }
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function globalClass(): BelongsTo
    {
        return $this->belongsTo(GlobalClass::class, 'global_class_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now()->toDateString());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
