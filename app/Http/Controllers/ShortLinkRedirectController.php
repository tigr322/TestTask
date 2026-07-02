<?php

namespace App\Http\Controllers;

use App\Services\RedirectService;

class ShortLinkRedirectController extends Controller
{
    public function __invoke(string $shortCode, RedirectService $redirectService)
    {
        return $redirectService->handle($shortCode);
    }
}
