<?php

return [
    'title' => 'Settings',
    'navigation' => [
        'label' => 'Settings',
        'group' => config('filament.navigation_group.settings'),
        'sort' => '1',
        'icon' => 'heroicon-o-cog',
        'folder' => 'site'
    ],
    'breadcrumbs' => [
        'Settings',
    ],
    'tool' => [
        "enable" => true
    ],
    'permission' => [
        'enable' => true,
        //  permission name
        // 'name' => 'list.settings'
        'name' => 'SettingPage'
    ]
];
