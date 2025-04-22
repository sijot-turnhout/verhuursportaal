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
            <div class="shape mt-5"></div>
                <footer id="footer" class="shadow-sm">
                    <div class="container">
                        <div class="row pt-3 pb-4">
                            <div class="col-4">
                                <div class="d-flex justify-content-start">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('img/sijot.png') }}" height="80" width=80  alt="...">
                                    </div>
                                    <div class="text-white ms-4">
                                        <ul class="small list-unstyled  mb-0">
                                            <li class="mb-2 border-bottom border-social"><h5 class="mb-0">Scouts en Gidsen (groepsnaam)</h5></li>
                                            <li class="mb-0"><x-heroicon-o-map-pin class="me-1 icon info-icon"/> adres, postcode naam v/d stad</li>
                                            <li class="mb-0"><x-heroicon-o-envelope class="me-1 icon info-icon"/> info@domain.tld</li>
                                            <li class="mb-0"><x-heroicon-o-device-phone-mobile class="me-1 icon info-icon"/> +32 000 00 00 00</li>
                                            <li class="mb-0">
                                                <a class="text-decoration-none" href="https://www.scoutsengidsenvlaanderen.be/sites/default/files/files/2015-statuut-van-de-vrijwilliger.pdf" target="_blank">
                                                    <x-heroicon-o-user-group class="me-1 icon info-icon"/> Statuut van de vrijwilliger
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-8">
                                <div class="d-flex justify-content-end">
                                    <div class="flex-column">
                                        <div class="mb-3">
                                            <h5 class="text-light border-bottom border-social">Socials</h5>
                                            <p class="text-footer mb-0">Wij zijn ook te vinden op de volgende social media kanalen.</p>
                                        </div>

                                        <div>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-1">
                                                    <a href="" class="btn btn-sm btn-social">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-1">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                                                        </svg>

                                                        Facebook
                                                    </a>
                                                </li>
                                                <li class="list-inline-item me-1">
                                                    <a href="" class="btn btn-sm btn-social">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-1">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                                                            <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
                                                        </svg>

                                                        X <span class="fst-italic">(Twitter)</span>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item me-1">
                                                    <a href="" class="btn btn-sm btn-social">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon me-1">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M6.335 5.144c-1.654 -1.199 -4.335 -2.127 -4.335 .826c0 .59 .35 4.953 .556 5.661c.713 2.463 3.13 2.75 5.444 2.369c-4.045 .665 -4.889 3.208 -2.667 5.41c1.03 1.018 1.913 1.59 2.667 1.59c2 0 3.134 -2.769 3.5 -3.5c.333 -.667 .5 -1.167 .5 -1.5c0 .333 .167 .833 .5 1.5c.366 .731 1.5 3.5 3.5 3.5c.754 0 1.637 -.571 2.667 -1.59c2.222 -2.203 1.378 -4.746 -2.667 -5.41c2.314 .38 4.73 .094 5.444 -2.369c.206 -.708 .556 -5.072 .556 -5.661c0 -2.953 -2.68 -2.025 -4.335 -.826c-2.293 1.662 -4.76 5.048 -5.665 6.856c-.905 -1.808 -3.372 -5.194 -5.665 -6.856z" />
                                                        </svg>

                                                        Bluesky
                                                    </a>
                                                </li>
                                                <li class="list-inline-item me-0">
                                                    <a href="" class="btn btn-sm btn-social">
                                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon me-1l">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 7.535v9.465a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-9.465l9.445 6.297l.116 .066a1 1 0 0 0 .878 0l.116 -.066l9.445 -6.297z" />
                                                            <path d="M19 4c1.08 0 2.027 .57 2.555 1.427l-9.555 6.37l-9.555 -6.37a2.999 2.999 0 0 1 2.354 -1.42l.201 -.007h14z" />
                                                        </svg>

                                                        Mail
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sub-footer">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="text-copyright small">
                                        &copy; Copyright {{ date('Y') }} - {{ config('app.name', 'Laravel') }}

                                        <a href="{{ route('legal.privacy') }}" data-pan="privacy-verklaring" class="float-end text-decoration-none">
                                            <x-heroicon-o-eye-slash class="icon me-1"/> Privacyverklaring
                                        </a>
                                        <a href="" class="float-end text-decoration-none me-3">
                                            <x-heroicon-o-chevron-double-up class="icon me-1"/> Terug naar boven
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            @endif
        </div>
    </body>

    @yield ('scripts')
</html>
