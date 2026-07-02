<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_delete_other_users_link(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)
            ->deleteJson(route('links.destroy', $link));

        $response->assertForbidden();

        $this->assertDatabaseHas('short_links', ['id' => $link->id]);
    }

    public function test_user_cannot_view_other_users_link_via_api(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)
            ->getJson(route('links.show', $link));

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_any_link_resource(): void
    {
        $link = ShortLink::factory()->create();

        $response = $this->getJson(route('links.show', $link));

        $response->assertUnauthorized();
    }
}
