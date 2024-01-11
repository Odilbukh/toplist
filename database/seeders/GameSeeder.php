<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Result;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::factory()->count(10000)->create();

        $firstMemberId = Member::min('id');
        $lastMemberId = Member::max('id');

        Member::all()->each(function () use ($firstMemberId, $lastMemberId) {
            Result::factory()->create(['member_id' => rand($firstMemberId, $lastMemberId)]);
        });
    }
}
