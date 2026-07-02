<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortLinkRequest;
use App\Models\ShortLink;
use App\Services\ShortLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShortLinkController extends Controller
{
    public function index(Request $request, ShortLinkService $service): JsonResponse
    {
        $links = $service->getUserLinks($request->user());

        return response()->json($links);
    }

    public function store(StoreShortLinkRequest $request, ShortLinkService $service): JsonResponse
    {
        $shortLink = $service->create($request->user(), $request->validated('original_url'));

        return response()->json([
            'id' => $shortLink->id,
            'original_url' => $shortLink->original_url,
            'short_url' => $shortLink->short_url,
            'short_code' => $shortLink->short_code,
            'clicks_count' => 0,
            'created_at' => $shortLink->created_at,
        ], 201);
    }

    public function show(ShortLink $shortLink): JsonResponse
    {
        Gate::authorize('view', $shortLink);

        $shortLink->loadCount('clicks');

        $clicks = $shortLink->clicks()
            ->latest('created_at')
            ->paginate(15);

        return response()->json([
            'link' => $shortLink,
            'clicks' => $clicks,
        ]);
    }

    public function destroy(ShortLink $shortLink): JsonResponse
    {
        Gate::authorize('delete', $shortLink);

        $shortLink->delete();

        return response()->json(null, 204);
    }
}
