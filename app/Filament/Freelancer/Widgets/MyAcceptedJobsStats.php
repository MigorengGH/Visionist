<?php

namespace App\Filament\Freelancer\Widgets;

use App\Models\Application;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class MyAcceptedJobsStats extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.freelancer.widgets.my-accepted-jobs-stats';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('period')
                    ->label('Time Period')
                    ->options([
                        'month' => 'This Month',
                        'year' => 'This Year',
                        'all' => 'All Time',
                    ])
                    ->default('month')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->updateStats()),
            ])
            ->statePath('data');
    }

    public function getStats(): array
    {
        $user = Auth::user();
        $period = $this->data['period'] ?? 'month';

        $query = Application::where('user_id', $user->id);

        switch ($period) {
            case 'month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $totalApplications = (clone $query)->count();
        $accepted = (clone $query)->where('status', 'accepted')->count();
        $pending = (clone $query)->where('status', 'pending')->count();
        $rejected = (clone $query)->where('status', 'rejected')->count();
        $acceptedRate = $totalApplications > 0 ? round(($accepted / $totalApplications) * 100, 1) : 0;

        $totalEarnings = (clone $query)->where('status', 'accepted')->sum('proposed_price');
        $avgEarnings = $accepted > 0 ? round($totalEarnings / $accepted, 2) : 0;
        $onlineJobs = (clone $query)->where('status', 'accepted')->whereHas('makejob', function ($q) {
            $q->where('is_online', true);
        })->count();
        $physicalJobs = (clone $query)->where('status', 'accepted')->whereHas('makejob', function ($q) {
            $q->where('is_online', false);
        })->count();

        return [
            'total_accepted' => $accepted,
            'pending' => $pending,
            'rejected' => $rejected,
            'accepted_rate' => $acceptedRate,
            'total_earnings' => $totalEarnings,
            'avg_earnings' => $avgEarnings,
            'online_jobs' => $onlineJobs,
            'physical_jobs' => $physicalJobs,
            'period' => $period,
        ];
    }

    public function updateStats(): void
    {
        $this->dispatch('update-stats');
    }
}
