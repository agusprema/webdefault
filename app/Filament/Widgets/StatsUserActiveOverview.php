<?php

namespace App\Filament\Widgets;

use Filament\Widgets\DoughnutChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class StatsUserActiveOverview extends DoughnutChartWidget
{
    protected static ?string $maxHeight = '300px';

    protected static ?array $options = [
        'plugins' => [
            'lineHeightAnnotation' => [
                'display' => true,
                'text' => 0,
                'color' => 'rgb(54, 162, 235)'
            ]
        ],
    ];

    protected function getHeading(): string
    {
        return 'Current User Active';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Current User Active',
                    'data' => [$this->getUserData()['user_active'], abs($this->getUserData()['user_active'] - $this->getUserData()['user'])],
                    'backgroundColor' => listColor(),
                ],
            ],
            'labels' => ['User Active', 'User not Active'],
        ];
    }

    protected function getUserData()
    {
        $userActive = DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('last_activity', '>=', now()->subHour(1)->timestamp)
            ->count();

        $user = User::all()->count();

        self::$options['plugins']['lineHeightAnnotation']['text'] = $userActive;
        self::$options['plugins']['lineHeightAnnotation']['color'] = listColor()[0];

        return [
            'user_active' => $userActive,
            'user' => $user,
        ];
    }
}
