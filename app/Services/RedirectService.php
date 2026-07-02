<?php

namespace App\Services;

use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;

class RedirectService
{
    public function __construct(
        private readonly ClickService $clickService,
    ) {}

    public function handle(string $shortCode): RedirectResponse
    {
        $shortLink = ShortLink::where('short_code', $shortCode)->first();

        if ($shortLink === null) {
            abort(404);
        }

        $this->clickService->record($shortLink, request());

        return redirect()->away($shortLink->original_url);
    }
}
