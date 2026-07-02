<?php

namespace App\Services;

use App\Models\Click;
use App\Models\ShortLink;
use Illuminate\Http\Request;

class ClickService
{
    public function record(ShortLink $shortLink, Request $request): Click
    {
        return Click::create([
            'short_link_id' => $shortLink->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
    }
}
