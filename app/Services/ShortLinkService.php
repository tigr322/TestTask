<?php

namespace App\Services;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ShortLinkService
{
    public function create(User $user, string $originalUrl): ShortLink
    {
        return ShortLink::create([
            'user_id' => $user->id,
            'original_url' => $originalUrl,
            'short_code' => $this->generateUniqueCode(),
        ]);
    }

    public function delete(ShortLink $shortLink): void
    {
        $shortLink->delete();
    }

    public function getUserLinks(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return ShortLink::where('user_id', $user->id)
            ->withCount('clicks')
            ->latest()
            ->paginate($perPage);
    }

    private function generateUniqueCode(int $length = 8): string
    {
        do {
            $code = Str::random($length);
        } while (ShortLink::where('short_code', $code)->exists());

        return $code;
    }

    public function generateCode(): string
    {
        return $this->generateUniqueCode();
    }
}
