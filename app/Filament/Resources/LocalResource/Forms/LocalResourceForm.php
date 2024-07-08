<?php

declare(strict_types=1);

namespace App\Filament\Resources\LocalResource\Forms;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

final readonly class LocalResourceForm
{
    public static function render(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('Algemene informatie'))
                    ->description('Alle basis informatie die benodigd is om een lokaal te registreren in het systeem')
                    ->icon('heroicon-o-home-modern')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed(fn(string $operation): bool => 'edit' === $operation)
                    ->columns(12)
                    ->schema([
                        TextInput::make('name')->label('Naam')->required()->unique(ignoreRecord: true)->columnSpan(8),
                        Textarea::make('description')->label('Beschrijving en of extra informatie')->rows(6)->columnSpan(12),
                        Checkbox::make('storage_location')->label('Dit lokaal fungeert als een opslag locatie van materieel')->columnSpan(12),
                    ])
                    ->compact(),
            ]);
    }
}
