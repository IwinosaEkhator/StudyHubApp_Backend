<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            'Mathematics',
            'English Literature',
            'Biology',
            'Chemistry',
            'Physics',
            'Computer Science',
            'Economics',
            'History',
            'Geography',
            'Philosophy',
            'Psychology',
            'Sociology',
            'Art & Design',
            'Music Theory',
            'Business Studies',
            'Accounting',
            'Engineering',
            'Medicine & Health Sciences',
            'Law',
            'Web Development',
            'Data Science',
            'Cybersecurity',
            'Personal Development',
            'Project Management',
            'Entrepreneurship',
            'Photography',
            'Creative Writing',
            'Cooking',
            'Gardening',
            'Travel & Culture',
        ];

        foreach ($topics as $name) {
            Subject::firstOrCreate(['name' => $name]);
        }

    }
}
