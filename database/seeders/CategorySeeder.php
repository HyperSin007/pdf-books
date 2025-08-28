<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Books about technology, programming, and computer science',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
                'description' => 'Scientific books and research materials',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business, entrepreneurship, and management books',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Literature',
                'slug' => 'literature',
                'description' => 'Classic and modern literature',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational materials and textbooks',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'History',
                'slug' => 'history',
                'description' => 'Historical books and documentaries',
                'color' => '#6B7280',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
