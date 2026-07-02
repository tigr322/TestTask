<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShortLinkResource\Pages;
use App\Filament\Resources\ShortLinkResource\RelationManagers\ClicksRelationManager;
use App\Models\ShortLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ShortLinkResource extends Resource
{
    protected static ?string $model = ShortLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationLabel = 'Мои ссылки';

    protected static ?string $modelLabel = 'Короткая ссылка';

    protected static ?string $pluralModelLabel = 'Короткие ссылки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Новая короткая ссылка')
                    ->description('Введите оригинальный URL для сокращения')
                    ->schema([
                        Forms\Components\TextInput::make('original_url')
                            ->label('Оригинальный URL')
                            ->required()
                            ->url()
                            ->maxLength(2048)
                            ->placeholder('https://example.com/very/long/url')
                            ->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('original_url')
                    ->label('Оригинальный URL')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn (ShortLink $record): string => $record->original_url)
                    ->copyable()
                    ->copyMessage('URL скопирован'),

                Tables\Columns\TextColumn::make('short_code')
                    ->label('Короткий код')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Код скопирован')
                    ->formatStateUsing(fn (string $state): string => url($state))
                    ->tooltip(fn (ShortLink $record): string => $record->short_url),

                Tables\Columns\TextColumn::make('clicks_count')
                    ->label('Переходы')
                    ->counts('clicks')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('copy')
                    ->label('Копировать')
                    ->icon('heroicon-o-clipboard')
                    ->action(function (ShortLink $record) {
                        Notification::make()
                            ->title('Ссылка скопирована')
                            ->success()
                            ->send();

                        return null;
                    })
                    ->extraAttributes(fn (ShortLink $record) => [
                        'x-data' => '{}',
                        'x-on:click' => "navigator.clipboard.writeText('{$record->short_url}')",
                    ]),

                Tables\Actions\ViewAction::make()
                    ->label('Статистика'),

                Tables\Actions\DeleteAction::make()
                    ->label('Удалить'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Удалить выбранные'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShortLinks::route('/'),
            'create' => Pages\CreateShortLink::route('/create'),
            'view' => Pages\ViewShortLink::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
