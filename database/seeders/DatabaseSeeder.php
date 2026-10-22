<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Loan;
use App\Models\MemberProfile;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Only creates demo data if it doesn't already exist (idempotent by email/code).
     */
    public function run(): void
    {
        // Admin
        $admin = User::withTrashed()->firstOrCreate(
            ['email' => 'admin@stardeck.local'],
            [
                'name' => 'Komandan Orion',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Members
        $members = [
            [
                'name' => 'Aria Nebula',
                'email' => 'aria@stardeck.local',
                'profile' => [
                    'phone' => '081234567890',
                    'rank_or_position' => 'Pilot Utama',
                    'station_module' => 'Modul Alpha',
                    'address' => 'Koridor A-12, Stasiun Bintang',
                ],
            ],
            [
                'name' => 'Budi Antares',
                'email' => 'budi@stardeck.local',
                'profile' => [
                    'phone' => '082345678901',
                    'rank_or_position' => 'Insinyur Sistem',
                    'station_module' => 'Modul Beta',
                    'address' => 'Koridor B-07, Stasiun Bintang',
                ],
            ],
            [
                'name' => 'Citra Vega',
                'email' => 'citra@stardeck.local',
                'profile' => [
                    'phone' => '083456789012',
                    'rank_or_position' => 'Peneliti Biologi',
                    'station_module' => 'Modul Gamma',
                    'address' => 'Koridor C-03, Stasiun Bintang',
                ],
            ],
            [
                'name' => 'Dian Cassiopeia',
                'email' => 'dian@stardeck.local',
                'profile' => [
                    'phone' => '084567890123',
                    'rank_or_position' => 'Teknisi Komunikasi',
                    'station_module' => 'Modul Delta',
                    'address' => null,
                ],
            ],
            [
                'name' => 'Eko Polaris',
                'email' => 'eko@stardeck.local',
                'profile' => [
                    'phone' => '085678901234',
                    'rank_or_position' => 'Spesialis Navigasi',
                    'station_module' => 'Modul Epsilon',
                    'address' => 'Koridor E-21, Stasiun Bintang',
                ],
            ],
        ];

        foreach ($members as $memberData) {
            $user = User::withTrashed()->firstOrCreate(
                ['email' => $memberData['email']],
                [
                    'name' => $memberData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'member',
                ]
            );

            if (!$user->memberProfile) {
                MemberProfile::create(array_merge(
                    ['user_id' => $user->id],
                    $memberData['profile']
                ));
            }
        }

        // Categories
        $categoryNames = [
            'Strategi' => 'Permainan yang membutuhkan perencanaan dan taktik.',
            'Party' => 'Permainan yang cocok untuk kelompok besar dan suasana santai.',
            'Kooperatif' => 'Permainan di mana pemain bekerja sama melawan sistem.',
            '2 Pemain' => 'Permainan yang dirancang atau cocok untuk dua orang.',
            'Console' => 'Perangkat konsol permainan elektronik.',
        ];

        $categories = [];
        foreach ($categoryNames as $name => $desc) {
            $categories[$name] = Category::firstOrCreate(
                ['name' => $name],
                ['description' => $desc]
            );
        }

        // Units
        $unitsData = [
            [
                'name' => 'Catan',
                'unit_code' => 'SD-BG-001',
                'type' => 'board_game',
                'condition' => 'Baik',
                'description' => 'Permainan strategi perdagangan dan pembangunan.',
                'categories' => ['Strategi'],
            ],
            [
                'name' => 'Catan',
                'unit_code' => 'SD-BG-002',
                'type' => 'board_game',
                'condition' => 'Baik',
                'description' => 'Salinan kedua Catan untuk keperluan cadangan.',
                'categories' => ['Strategi', '2 Pemain'],
            ],
            [
                'name' => 'Codenames',
                'unit_code' => 'SD-BG-003',
                'type' => 'board_game',
                'condition' => 'Baik',
                'description' => 'Permainan tebak kata untuk tim.',
                'categories' => ['Party'],
            ],
            [
                'name' => 'Pandemic',
                'unit_code' => 'SD-BG-004',
                'type' => 'board_game',
                'condition' => 'Baik',
                'description' => 'Permainan kooperatif melawan wabah global.',
                'categories' => ['Kooperatif', 'Strategi'],
            ],
            [
                'name' => 'PlayStation 5',
                'unit_code' => 'SD-CN-001',
                'type' => 'console',
                'condition' => 'Baik',
                'description' => 'Konsol PlayStation 5 lengkap dengan kontroler.',
                'categories' => ['Console'],
            ],
            [
                'name' => 'PlayStation 5',
                'unit_code' => 'SD-CN-002',
                'type' => 'console',
                'condition' => 'Baik',
                'description' => 'PS5 unit kedua untuk area rekreasi.',
                'categories' => ['Console'],
            ],
            [
                'name' => 'Nintendo Switch',
                'unit_code' => 'SD-CN-003',
                'type' => 'console',
                'condition' => 'Baik',
                'description' => 'Konsol portabel Nintendo Switch.',
                'categories' => ['Console', '2 Pemain'],
            ],
        ];

        foreach ($unitsData as $unitData) {
            $unit = Unit::withTrashed()->firstOrCreate(
                ['unit_code' => $unitData['unit_code']],
                [
                    'name' => $unitData['name'],
                    'type' => $unitData['type'],
                    'condition' => $unitData['condition'],
                    'description' => $unitData['description'],
                ]
            );

            // Attach categories if not already attached
            $catIds = [];
            foreach ($unitData['categories'] as $catName) {
                if (isset($categories[$catName])) {
                    $catIds[] = $categories[$catName]->id;
                }
            }
            $unit->categories()->syncWithoutDetaching($catIds);
        }
    }
}
