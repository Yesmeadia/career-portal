<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'logo',
        'cover_image',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'status',
        'timezone',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($school) {
            if (empty($school->slug)) {
                $school->slug = Str::slug($school->name);
            }
            if (empty($school->code)) {
                $school->code = strtoupper(Str::slug($school->name));
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('media/' . $this->logo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=2563EB&background=DBEAFE';
    }
}
