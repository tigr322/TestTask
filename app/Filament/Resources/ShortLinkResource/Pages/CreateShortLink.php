<?php

namespace App\Filament\Resources\ShortLinkResource\Pages;

use App\Filament\Resources\ShortLinkResource;
use App\Services\ShortLinkService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateShortLink extends CreateRecord
{
    protected static string $resource = ShortLinkResource::class;

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        $this->validate();

        $service = app(ShortLinkService::class);
        $shortLink = $service->create(auth()->user(), $this->data['original_url']);

        $this->record = $shortLink;

        $this->form->model($this->getRecord())->saveRelationships();

        $this->redirect($this->getRedirectUrl());
    }

    protected function handleRecordCreation(array $data): Model
    {
        return app(ShortLinkService::class)->create(auth()->user(), $data['original_url']);
    }
}
