<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Menggunakan akun pertama (akunmu) sebagai pembuat laporan
            'user_id' => 1, 
            
            // fake()->sentence() membuat kalimat acak untuk judul
            'title' => fake()->sentence(6), 
            
            // fake()->paragraph() membuat paragraf acak untuk deskripsi
            'description' => fake()->paragraph(3), 
            
            // Memilih secara acak salah satu dari 3 kategori kita
            'category' => fake()->randomElement(['sampah', 'fasilitas', 'flora_fauna']), 
            
            // Membuat nama kota/provinsi acak
            'location_name' => fake()->city() . ', ' . fake()->state(), 
            
            // Status diacak juga agar kartunya berwarna-warni
            'status' => fake()->randomElement(['pending', 'verified', 'resolved']), 
        ];
    }
}
