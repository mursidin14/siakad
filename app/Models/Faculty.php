<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Dom\Attr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    use HasFactory, Sluggable;
    protected $fillable = [
        'name',
        'code',
        'logo',
        // 'slug' => slug manual
    ];


    // slug library
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ],
        ];
    }

    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    public function departements()
    {
        return $this->hasMany(Departement::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

        public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function($query, $search) {
            $query->whereAny([
            'name',
            'code',
        ], 'REGEXP', $search);
        });
    }

        public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function($query) use ($sorts) {
            $query->orderBy($sorts['field'], $sorts['direction']);
        });
    }

}
