<?php

namespace App\Filament\Resources\EmailRuleResource\Pages;

use App\Filament\Resources\EmailRuleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailRule extends CreateRecord
{
    protected static string $resource = EmailRuleResource::class;

//    protected function beforeValidate(): void
//    {
//        dd($this->data);
//        // Runs before the form fields are validated when the form is submitted.
//    }
}
