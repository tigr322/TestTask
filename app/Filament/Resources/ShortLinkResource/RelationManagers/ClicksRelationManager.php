<?php

namespace App\Filament\Resources\ShortLinkResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    protected static ?string $title = 'Статистика переходов';

    protected static ?string $recordTitleAttribute = 'ip';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ip')
            ->columns([
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP-адрес')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('user_agent')
                    ->label('User-Agent')
                    ->limit(60)
                    ->tooltip(fn ($record): string => $record->user_agent ?? ''),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата и время')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
