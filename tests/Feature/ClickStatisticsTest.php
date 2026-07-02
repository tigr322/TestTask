<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClickStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_click_statistics_for_own_link(): void
    {
        $user = User::factory()->create();
        $link = ShortLink::factory()->create([
            'user_id' => $user->id,
            'short_code' => 'stat01',
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('links.show', $link));

        $response->assertOk();
        $response->assertJsonStructure([
            'link' => ['id', 'original_url', 'short_code', 'short_url'],
            'clicks',
        ]);
    }

    public function test_user_cannot_view_statistics_of_other_users_link(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $link = ShortLink::factory()->create([
            'user_id' => $otherUser->id,
            'short_code' => 'stat02',
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('links.show', $link));

        $response->assertForbidden();
    }
}
