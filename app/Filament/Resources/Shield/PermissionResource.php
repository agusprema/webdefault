<?php

namespace App\Filament\Resources\Shield;

use App\Filament\Resources\Shield\PermissionResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Support\Str;
use Closure;

class PermissionResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('filament-shield::filament-shield.field.name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('guard_name')
                                    ->label(__('filament-shield::filament-shield.field.guard_name'))
                                    ->default(Utils::getFilamentAuthGuard())
                                    ->nullable()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('select_all')
                                    ->onIcon('heroicon-s-shield-check')
                                    ->offIcon('heroicon-s-shield-exclamation')
                                    ->label(__('filament-shield::filament-shield.field.select_all.name'))
                                    ->helperText(__('filament-shield::filament-shield.field.select_all.message'))
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state) {

                                        collect(config('filament-shield.permission_prefixes.resource'))->each(function ($prefix) use ($set, $state) {
                                            $set('prefix.' . $prefix, $state);
                                        });

                                        if (!$state) {
                                            $set('select_all', false);
                                        }

                                        static::refreshEntitiesStatesViaSelectAll($set, $state);
                                    })
                                    ->dehydrated(fn ($state): bool => $state),
                            ])->columns([
                                'sm' => 2,
                                'lg' => 3,
                            ]),
                    ]),
                Forms\Components\Tabs::make('Permissions')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('filament-shield::filament-shield.prefix'))
                            ->reactive()
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema(
                                        collect(static::getPrefixPermission())->reduce(function ($prefixs, $prefix, $prefixey) {
                                            $prefixs[] = Forms\Components\Checkbox::make('prefix.' . $prefixey)
                                                ->label($prefix)->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                                                    if (!$state) {
                                                        $set('select_all', false);
                                                    }

                                                    static::refreshSelectAllStateViaEntities($set, $get);
                                                })
                                                ->dehydrated(fn ($state): bool => $state);

                                            return $prefixs;
                                        }, [])
                                    ),
                            ])
                    ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('name')
                    ->label(__('filament-shield::filament-shield.column.name'))
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['primary'])
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('guard_name')
                    ->label(__('filament-shield::filament-shield.column.guard_name')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament-shield::filament-shield.column.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getModel(): string
    {
        return Utils::getPermissionModel();
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.permission');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.permissions');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return Utils::isResourceNavigationRegistered();
    }

    protected static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? config('filament.navigation_group.user')
            : '';
    }

    protected static function getNavigationLabel(): string
    {
        return __('filament-shield::filament-shield.nav.permission.label');
    }

    protected static function getNavigationIcon(): string
    {
        return __('filament-shield::filament-shield.nav.permission.icon');
    }

    protected static function getNavigationSort(): ?int
    {
        return Utils::getResourceNavigationSort();
    }

    public static function getSlug(): string
    {
        return (string) config('filament-shield.shield_resource_permission.slug', 'permission');
    }

    protected static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? static::getModel()::count()
            : null;
    }

    protected static function getPrefixPermission()
    {
        return collect(config('filament-shield.permission_prefixes.resource'))->reduce(function ($prefixs, $prefix) {
            $prefixs[$prefix] = Str::headline($prefix);
            return $prefixs;
        }, collect())->toArray();
    }

    protected static function refreshSelectAllStateViaEntities(Closure $set, Closure $get): void
    {
        $entitiesStates = collect(static::getPrefixPermission())
            ->map(function ($entity, $keyentitty) use ($get) {
                return (bool) $get('prefix.' . $keyentitty);
            });

        if ($entitiesStates->containsStrict(false) === false) {
            $set('select_all', true);
        }

        if ($entitiesStates->containsStrict(false) === true) {
            $set('select_all', false);
        }
    }

    protected static function refreshEntitiesStatesViaSelectAll(Closure $set, $state): void
    {
        $entitiesStates = collect(config('filament-shield.permission_prefixes.resource'))->map(function ($prefix) use ($set, $state) {
            $set('prefix.' . $prefix, $state);
        });

        if ($entitiesStates->containsStrict(false) === false) {
            $set('select_all', true);
        }

        if ($entitiesStates->containsStrict(false) === true) {
            $set('select_all', false);
        }
    }

    public static function canGloballySearch(): bool
    {
        return Utils::isResourceGloballySearchable() && count(static::getGloballySearchableAttributes()) && static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
