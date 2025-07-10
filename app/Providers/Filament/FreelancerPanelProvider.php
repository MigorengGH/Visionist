<?php

namespace App\Providers\Filament;

use App\Filament\Freelancer\Pages\Auth\FreelancerRegister;
use App\Filament\Freelancer\Pages\Auth\FreelancerEditProfile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use PHPUnit\Framework\Attributes\Medium;
use Filament\Support\Enums\MaxWidth;


class FreelancerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('freelancer')
            ->path('freelancer')
            ->authGuard('web')
            ->registration(FreelancerRegister::class)
            ->login()
            ->profile(FreelancerEditProfile::class, false)
            ->emailVerification()
            ->passwordReset()
            ->brandName('Visionist')
            ->brandLogoHeight('70px')
            ->brandLogo(asset('storage/logoV/logo-light.svg')) // Ensure this file exists at the specified path
            ->darkModeBrandLogo(asset('storage/logoV/logo-dark.svg')) // Ensure this file exists at the specified path
            //collaped sider bar
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::SevenExtraLarge)

            ->authMiddleware([Authenticate::class])
            ->colors([
                'primary' => Color::Amber,

            ])
            ->discoverResources(in: app_path('Filament/Freelancer/Resources'), for: 'App\\Filament\\Freelancer\\Resources')
            ->discoverPages(in: app_path('Filament/Freelancer/Pages'), for: 'App\\Filament\\Freelancer\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Freelancer/Widgets'), for: 'App\\Filament\\Freelancer\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Freelancer\Widgets\MyStatsOverview::class,
                \App\Filament\Freelancer\Widgets\TotalEarningChartWidget::class,
                \App\Filament\Freelancer\Widgets\ApplicationStatusPieWidget::class,
                \App\Filament\Freelancer\Widgets\MyJobsStats::class,
                \App\Filament\Freelancer\Widgets\TopOpenJobsByBudget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
