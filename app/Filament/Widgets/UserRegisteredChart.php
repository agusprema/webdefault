<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class UserRegisteredChart extends LineChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'year';

    protected function getHeading(): string
    {
        return 'User Registered';
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $data = $this->getDataUser();

        return [
            'datasets' => [
                [
                    'label' => 'User Registered',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => listColor(),
                    'fill' => true,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $this->ReadByHuman($value->date)),
        ];
    }

    protected function getDataUser()
    {
        switch ($this->filter) {
            case 'year':
                return Trend::model(User::class)
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->count();
                break;
            case 'week':
                return Trend::model(User::class)
                    ->between(
                        start: now()->startOfWeek(),
                        end: now()->endOfWeek(),
                    )
                    ->perDay()
                    ->count();
                break;
            default:
                return Trend::model(User::class)
                    ->between(
                        start: now()->startOfMonth(),
                        end: now()->endOfMonth(),
                    )
                    ->perday()
                    ->count();
        }
    }

    protected function ReadByHuman($date)
    {
        switch ($this->filter) {
            case 'year':
                return Carbon::createFromFormat('Y-m', $date)->format('F');
                break;
            case 'week':
                return Carbon::createFromFormat('Y-m-d', $date)->format('l');
                break;
            default:
                return $date;
        }
    }
}
