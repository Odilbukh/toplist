<?php

namespace Tests\Unit;

use App\Models\Member;
use App\Models\Result;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testSaveResult()
    {
        $response = $this->postJson('/api/save-result', [
            'email' => 'test@example.com',
            'milliseconds' => 1000,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('results', [
            'milliseconds' => 1000,
        ]);
    }

    public function testGetTopResults()
    {
        Member::class->factory()->count(5)->create()->each(function ($member) {
            Result::class->factory()->create(['member_id' => $member->id]);
        });

        $response = $this->getJson('/api/top-results');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'top' => [
                        '*' => [
                            'member_id',
                            'milliseconds',
                            'place',
                        ],
                    ],
                    'self' => [
                        'member_id',
                        'milliseconds',
                        'place',
                    ],
                ],
            ]);
    }
}

