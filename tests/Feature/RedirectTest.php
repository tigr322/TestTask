<?php

namespace Tests\Feature;

use App\Models\Click;
use App\Models\ShortLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_short_code_redirects_to_original_url(): void
    {
        $link = ShortLink::factory()->create([
            'short_code' => 'abc123',
            'original_url' => 'https://example.com/target',
        ]);

        $response = $this->get('/abc123');

        $response->assertRedirect('https://example.com/target');
    }

    public function test_redirect_records_click(): void
    {
        $link = ShortLink::factory()->create([
            'short_code' => 'xyz789',
            'original_url' => 'https://example.com/track',
        ]);

        $this->assertDatabaseMissing('clicks', [
            'short_link_id' => $link->id,
        ]);

        $this->get('/xyz789', [
            'HTTP_USER_AGENT' => 'TestBrowser/1.0',
        ]);

        $this->assertDatabaseHas('clicks', [
            'short_link_id' => $link->id,
            'user_agent' => 'TestBrowser/1.0',
        ]);
    }

    public function test_invalid_short_code_returns_404(): void
    {
        $response = $this->get('/nonexistent');

        $response->assertNotFound();
    }

    public function test_redirect_records_ip_address(): void
    {
        $link = ShortLink::factory()->create([
            'short_code' => 'ip1234',
            'original_url' => 'https://example.com/ip-test',
        ]);

        $this->get('/ip1234');

        $click = Click::where('short_link_id', $link->id)->first();

        $this->assertNotNull($click);
        $this->assertNotNull($click->ip);
    }

    public function test_multiple_clicks_are_recorded(): void
    {
        $link = ShortLink::factory()->create([
            'short_code' => 'multi01',
            'original_url' => 'https://example.com/multi',
        ]);

        $this->get('/multi01');
        $this->get('/multi01');
        $this->get('/multi01');

        $this->assertDatabaseCount('clicks', 3);
    }
}
