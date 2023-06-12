<?php

namespace App\Filament\Resources\Shield\PermissionResource\Pages;

use App\Filament\Resources\Shield\PermissionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;
use BezhanSalleh\FilamentShield\Support\Utils;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
