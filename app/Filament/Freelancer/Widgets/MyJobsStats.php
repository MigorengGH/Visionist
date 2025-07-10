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

    protected int | string | array $columnSpan = '2';
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
        $totalBudget = (clone $query)->where('status', 'accepted')->sum('budget');
        $pendingApplications = 0;
        $totalProposedPrice = 0;
        foreach ((clone $query)->get() as $job) {
            $pendingApplications += $job->applications()->where('status', 'pending')->count();
            $totalProposedPrice += $job->applications()->where('status', 'accepted')->sum('proposed_price');
        }

        return [
            'total_jobs' => $totalJobs,
            'open_jobs' => $openJobs,
            'total_budget' => $totalBudget,
            'total_proposed_price' => $totalProposedPrice,
            'pending_applications' => $pendingApplications,
            'period' => $period,
        ];
    }

    public function updateStats(): void
    {
        $this->dispatch('update-stats');
    }

    public static function getColumns(): int
    {
        return 3; // 3 columns for 3 widgets in a row
    }
}
