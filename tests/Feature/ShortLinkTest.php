<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_links_index(): void
    {
        $response = $this->get(route('links.index'));

        $response->assertRedirect();
    }

    public function test_authenticated_user_can_access_links_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('links.index'));

        $response->assertOk();
    }

    public function test_user_can_create_short_link(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('links.store'), [
                'original_url' => 'https://example.com/test-page',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('short_links', [
            'user_id' => $user->id,
            'original_url' => 'https://example.com/test-page',
        ]);
    }

    public function test_user_cannot_create_short_link_with_invalid_url(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('links.store'), [
                'original_url' => 'not-a-valid-url',
            ]);

        $response->assertStatus(422);
    }

    public function test_user_can_only_see_own_links_in_index(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $myLink = ShortLink::factory()->create(['user_id' => $user->id]);
        ShortLink::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)
            ->getJson(route('links.index'));

        $response->assertOk();

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals($myLink->id, $data[0]['id']);
    }

    public function test_user_can_delete_own_link(): void
    {
        $user = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->deleteJson(route('links.destroy', $link));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('short_links', ['id' => $link->id]);
    }

    public function test_user_can_view_own_link_statistics(): void
    {
        $user = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->getJson(route('links.show', $link));

        $response->assertOk();
        $response->assertJsonStructure(['link', 'clicks']);
    }
}
