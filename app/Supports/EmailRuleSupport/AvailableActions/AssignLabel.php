<?php

namespace App\Supports\EmailRuleSupport\AvailableActions;

use App\Models\Email;
use App\Models\Label;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;

class AssignLabel implements AvailableActionsInterface
{
    public static function executeAction(Email $email, array $settings)
    {
        $label = Label::find($settings['label']);
        dd($label);
        // TODO: Implement executeAction() method.
    }

    public static function getBlock(): Block
    {
        return Block::make('AssignLabel')
            ->icon('heroicon-o-tag')
            ->schema([
                Select::make('label')
                    ->options(Label::all()->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }

    public static function getFakeData(): array
    {
        return [
            'label' => Label::factory()->create()->id,
        ];
    }
}
