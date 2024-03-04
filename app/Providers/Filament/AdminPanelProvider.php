<?php

namespace App\Providers\Filament;

use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\DayOffResource;
use App\Filament\Resources\PunchResource;
use App\Filament\Resources\UserResource;
use Filament\Enums\ThemeMode;
use Filament\Forms;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->passwordReset()
            ->profile(isSimple: false)
            ->loginRouteSlug('login')
            ->registrationRouteSlug('register')
            ->passwordResetRoutePrefix('password-reset')
            ->passwordResetRequestRouteSlug('request')
            ->passwordResetRouteSlug('reset')
            ->favicon(asset('images/favicon.png'))
            ->brandLogo(asset('images/logo-new.png'))
            ->brandLogoHeight('3.5rem')
            ->font('Poppins')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'primary' => 'rgb(138,43,226)',
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->globalSearchKeyBindings([
                'ctrl+k',
                'command+k',
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationItems([
                NavigationItem::make('OnHappy')
                    ->url('https://appv2.onhappy.com.br/', true)
                    ->icon('heroicon-o-globe-alt')
                    ->group('External')
                    ->sort(2),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url('admin/user/settings')
                    ->icon('heroicon-o-cog-6-tooth'),
                'logout' => MenuItem::make()->label('Logout'),
            ])
            ->brandName('Portal RH')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
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
            ])
            //->topNavigation()
            // Customize manual navigation
            //->navigation(function (NavigationBuilder $navigation): NavigationBuilder {
            //    return $navigation->items([
            //        NavigationItem::make('Inicio')
            //            ->icon('heroicon-o-home')
            //            ->url(fn (): string => Pages\Dashboard::getUrl()),
            //        NavigationItem::make('Usuários'),
            //        ...UserResource::getNavigationItems(),
            //        ...CompanyResource::getNavigationItems(),
            //        ...PunchResource::getNavigationItems(),
            //        ...DayOffResource::getNavigationItems(),
            //    ]);
            //})
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
