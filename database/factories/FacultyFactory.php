<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculty>
 */
class FacultyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->unique()->randomElement(['Fakultas Teknik', 'Fakultas Ekonomi', 'Fakultas Seni']),
            'slug' => str()->slug($name),
            'code' => str()->random(6),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Faculty $faculty) {
            $departements = match ($faculty->name) {
                'Fakultas Teknik' => [
                    ['name' => $name = 'Teknik Informatika', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                    ['name' => $name = 'Teknik Industri', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                    ['name' => $name = 'Teknik Sipil', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                ],
                'Fakultas Ekonomi' => [
                    ['name' => $name = 'Manajemen', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                    ['name' => $name = 'Akuntansi', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                    ['name' => $name = 'Ekonomi Pembangunan', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                ],
                'Fakultas Seni' => [
                    ['name' => $name = 'Seni Rupa', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                    ['name' => $name = 'Seni Musik', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                    ['name' => $name = 'Seni Tari', 'slug' => str()->slug($name), 'code' => strtoupper(str()->random(6))],
                ],
                default => [],
            };

            foreach ($departements as $departement) {
                $faculty->departements()->create([
                    'name' => $departement['name'],
                    'slug' => $departement['slug'],
                    'code' => $departement['code'],
                ]);
            }
        });
    }
}
