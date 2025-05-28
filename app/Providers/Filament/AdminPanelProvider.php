<?php

namespace App\Providers\Filament;

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
use App\Models\User;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Filament\Support\Enums\MaxWidth;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->topNavigation()
            ->databaseNotifications()
            ->maxContentWidth(MaxWidth::SevenExtraLarge)


            ->plugin(
                BreezyCore::make()
                ->myProfile()
                ->enableTwoFactorAuthentication(
                    force: false,)
            )
            ->login()
            ->passwordReset()
            ->brandName('Visionist')
            ->brandLogoHeight('70px')
            ->brandLogo(asset('storage/logoV/logo-light.svg')) // Ensure this file exists at the specified path
            ->darkModeBrandLogo(asset('storage/logoV/logo-dark.svg')) // Ensure this file exists at the specified path
            //collaped sider bar
            ->sidebarFullyCollapsibleOnDesktop()
            //only isAdmin can access
            ->authMiddleware([Authenticate::class])

            ->colors([
                'primary' => Color::Indigo,



            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
                \App\Filament\Admin\Widgets\StatsOverview::class,
                \App\Filament\Admin\Widgets\ArtworksChart::class,
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
