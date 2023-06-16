<?php

namespace App\Filament\Resources\Shield\PermissionResource\Pages;

use App\Filament\Resources\Shield\PermissionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        if (!empty($data['prefix'])) {
            $permissions = collect($data['prefix'])->reduce(function ($permissions, $key, $item) use ($data) {
                $permissions[] = [
                    'name' => $item . '_' . $data['name'],
                    'guard_name' => $data['guard_name']
                ];

                return $permissions;
            }, collect())->toArray();
        } else {
            $permissions[] = [
                'name' => $data['name'],
                'guard_name' => $data['guard_name']
            ];
        }

        foreach ($permissions as $attributes) {
            $record = static::getModel()::firstOrCreate($attributes);
        }

        return $record;
    }
}
