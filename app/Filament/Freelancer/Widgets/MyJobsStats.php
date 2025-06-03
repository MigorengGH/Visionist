<?php

namespace App\Filament\Freelancer\Widgets;

use App\Models\Makejob;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Models\Application;

class MyJobsStats extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.freelancer.widgets.my-jobs-stats';

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

        $query = Makejob::where('user_id', $user->id);

        switch ($period) {
            case 'month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $totalJobs = $query->count();
        $openJobs = (clone $query)->where('status', 'open')->count();
        $dealJobs = Application::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->when($period === 'month', function ($q) {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            })
            ->when($period === 'year', function ($q) {
                $q->whereYear('created_at', now()->year);
            })
            ->count();
        $totalBudget = $query->sum('budget');
        $totalApplications = 0;
        $totalProposalPrice = 0;
        foreach ((clone $query)->get() as $job) {
            $totalApplications += $job->applications()->count();
            $totalProposalPrice += $job->applications()->sum('proposed_price');
        }
        $avgBudget = $totalJobs > 0 ? round($totalBudget / $totalJobs, 2) : 0;
        $onlineJobs = (clone $query)->where('is_online', true)->count();
        $physicalJobs = (clone $query)->where('is_online', false)->count();

        return [
            'total_jobs' => $totalJobs,
            'open_jobs' => $openJobs,
            'deal_jobs' => $dealJobs,
            'total_budget' => $totalBudget,
            'avg_budget' => $avgBudget,
            'total_applications' => $totalApplications,
            'total_proposal_price' => $totalProposalPrice,
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
