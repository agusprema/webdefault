<?php

namespace App\Filament\Resources\Shield\PermissionResource\Pages;

use App\Filament\Resources\Shield\PermissionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Table;
use Filament\Tables;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
