<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

        <!--Favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}"/>
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}"/>
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}"/>
        <link rel="manifest" href="{{ asset('img/favicon//site.webmanifest') }}"/>
        <link rel="mask-icon" href="{{ asset('img/favicon/safari-pinned-tab.svg') }}" color="#5bbad5"/>

        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-lg navbar-dark navbar-gradient shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('img/sijot.png') }}" alt="Bootstrap" width="22" height="22" class="me-2"> {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('filament.admin.auth.login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('filament.admin.auth.login') }}">
                                    <x-heroicon-o-user-circle class="icon me-1"/> {{ __('Login') }}
                                </a>
                            </li>
                                @endif

                                @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                                @endif
                            @else
                            <li class="nav-item dropdown show">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <x-heroicon-o-user-circle class="icon icon-page-title me-1"/> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="">
                                        <x-heroicon-o-adjustments-vertical class="icon me-1"/> Accountbeheer
                                    </a>

                                    <a class="dropdown-item" href="{{ route('filament.admin.pages.dashboard') }}">
                                        <x-heroicon-o-circle-stack class="icon me-1"/> Beheerconsole
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('filament.admin.auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <x-heroicon-o-power class="icon text-danger me-1"/> {{ __('Uitloggen') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('filament.admin.auth.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main>
                {{ $slot }}
            </main>


            @if (!active(['feedback*', 'debug/feedback']))
                <footer id="footer" class="shadow-sm">
                    <div class="container">
                        <div class="row py-3">
                            <div class="col-lg-12">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('img/sijot.png') }}" height="80" width=80  alt="...">
                                    </div>
                                    <div class="text-white ms-4">
                                        <ul class="small list-unstyled  mb-0">
                                            <li class="mb-0">Scouts en Gidsen Sint-Joris</li>
                                            <li class="mb-0">Sint-Jorislaan 11, 2300 Turnhout</li>
                                            <li class="mb-0">info@domain.tld</li>
                                            <li class="mb-0">+32 000 00 00 00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <hr class="footer-breakline">

                                <p class="text-copyright small">
                                    Copyright {{ date('Y') }} &copy; {{ config('app.name', 'Laravel') }}

                                    <a href="{{ route('legal.privacy') }}" class="float-end text-decoration-none">
                                        <x-heroicon-o-eye-slash class="icon me-1"/> Privacyverklaring
                                    </a>
                                    <a href="" class="float-end text-decoration-none me-3">
                                        <x-heroicon-o-chevron-double-up class="icon me-1"/> Terug naar boven
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </footer>
            @endif
        </div>
    </body>

    @yield ('scripts')
</html>
