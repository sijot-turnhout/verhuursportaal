<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Factuur :number', ['number' => '']) }} | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Favicons --}}
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">


    <!-- Styles -->
    @vite(['resources/js/app.js', 'resources/sass/invoice.scss'])
    @livewireStyles
</head>
<body class="d-flex flex-column bg-white h-100">
<div id="app"> {{-- CONTENT --}}
    <div class="container my-4">
        <div class="row">
            <div class="col-9">
                <h3 class="text-brown font-weight-bold">{{ __('Factuur :nr', ['nr' => $record->payment_reference]) }}</h3>
                <p class="text-muted"><small>{{ $record->description }}</small></p>
            </div>

            <div class="col-3">
                <img width="70" height="70"  class="float-end" src="{{  asset('img/sijot.png') }}" alt="">
            </div>
        </div>

        <div class="card border-0 bg-light mt-4">
            <div class="card-body">
                <div class="row ">
                    <div class="col-4 mb-4">
                        <p class="font-weight-bold h6">{{ __('Factuurdatum') }}</p>
                        <p class="card-text"><code>{{ $record->payment_reference }}</code></p>
                    </div>

                    <div class="col-4 mb-4">
                        <p class="font-weight-bold h6">{{ __('Factuurdatum') }}</p>
                        <p class="card-text">{{ $record->created_at->format('d/m/Y') }}</p>
                    </div>

                    <div class="col-4 mb-4">
                        <p class="font-weight-bold h6">{{ __('Vervaldatum') }}</p>
                        <p class="card-text">{{ optional($record->due_at)->format('d/m/Y') ?? '-' }}</p>
                    </div>

                    <div class="col-12">
                        <p class="font-weight-bold h6">{{ __('Klantgegevens') }}</p>

                        <ul class="list-unstyled mb-0">
                            <li><x-heroicon-o-user-circle class="icon me-1"/> {{ $record->customer->name }}</li>
                            <li><x-heroicon-o-home-modern class="icon me-1"/> {{ $record->customer->address ?? trans('N.V.T of onbekend') }}</li>
                            <li><x-heroicon-o-envelope class="icon me-1"/> {{ $record->customer->email }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 mb-3">
                <h5 class="text-brown font-weight-bold">{{ __('Informatie over de betalingsopdracht of gefactureerde diensten.') }}</h5>
                <p class="mb-0 text-muted">
                    {!! __('Gelieve de betaling uit te voeren met het Factuur nr <strong>(:nr)</strong> dat bovenaan vermeld staat als mededeling.', ['nr' => $record->payment_reference]) !!}
                    {!! __('Op het volgende rekeningnummer. <strong>:rekeningnr</strong>', ['rekeningnr' => config('reiziger.billing.invoice.bank_number', 'BE00 0000 0000 0000')]) !!}
                </p>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 mb-3">
                <h4 class="text-brown font-weight-bold">Overzicht</h4>
            </div>

            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="border-top-0">{{ __('Product en of dienst') }}</th>
                                <th scope="col" class="border-top-0">{{ __('Aantal') }}</th>
                                <th scope="col" class="border-top-0">{{ __('Eenheidsprijs') }}</th>
                                <th scope="col" class="border-top-0">
                                    <span class="float-end">
                                        {{ __('Subtotaal') }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($record->invoiceLines as $invoiceLine)
                                <tr>
                                    <td>{{ $invoiceLine->name }}</td>
                                    <td>{{ (int) $invoiceLine->quantity }}</td>
                                    <td>{{ $invoiceLine->unit_price }}€</td>
                                    <td>
                                        <span class="float-end">
                                            @if ($invoiceLine->type === \App\Filament\Resources\InvoiceResource\Enums\BillingType::Discount)
                                                -{{ $invoiceLine->total_price }}€
                                            @else
                                                {{ $invoiceLine->total_price }}€
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="3" class="border-bottom-0">
                                    <span class="float-end text-brown"><strong>{{ __('SUBTOTAAL') }}</strong></span>
                                </td>
                                <td colspan="1" class="border-bottom-0">
                                    <span class="float-end fw-bold">{{ $record->getSubTotal() }}€</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="border-bottom-0">
                                    <span class="float-end text-brown"><strong>{{ __('VERMINDERING') }}</strong></span>
                                </td>
                                <td colspan="1" class="border-bottom-0">
                                    <span class="float-end fw-bold">- {{ $record->getDiscountTotal() }}€</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="border-bottom-0">
                                    <span class="float-end text-brown"><strong>{{ __('TE BETALEN') }}</strong></span>
                                </td>
                                <td colspan="1" class="border-bottom-0">
                                    <span class="float-end fw-bold">{{ $record->invoiceTotal }}€</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-6">
                <ul class="list-unstyled">
                    <li class="fw-bold text-brown">{{ __('Contactgegevens') }}</li>
                    <li><x-heroicon-o-user-circle class="icon me-1"/> {{ __('Sint-Joris Turnhout') }}</li>
                    <li><x-heroicon-o-home-modern class="icon me-1"/> Sint-Jorislaan 11, 2300 Turnhout</li>
                    <li><x-heroicon-o-envelope class="icon me-1"/> sintjorisverhuur@gmail.com</li>
                    <li><x-heroicon-o-identification class="icon me-1"/> {{ trans('Ondernemingsnr. :number', ['number' => config('reiziger.billing.invoice.company_number', '0123 456 789')]) }}</li>
                </ul>
            </div>

            <div class="col-6">
                <p class="mb-0 fw-bold text-brown">{{ __('Betaalgegevens') }}</p>
                <dl class="row">
                    <dt class="font-weight-normal col-sm-5">{{ __('Bank') }}</dt>
                    <dd class="col-sm-7 font-weight-bold mb-0">{{ config('reiziger.billing.invoice.bank_name', 'BNP Fortis') }}</dd>
                    <dt class="font-weight-normal col-sm-5">{{ __('SWIFT/BIC') }}</dt>
                    <dd class="col-sm-7 font-weight-bold mb-0">{{ config('reiziger.billing.invoice.bank_swift_bic', 'GEBABEBB') }}</dd>
                    <dt class="font-weight-normal col-sm-5">{{ __('IBAN') }}</dt>
                    <dd class="col-sm-7 font-weight-bold">{{ config('reiziger.billing.invoice.bank_number', 'BE00 0000 0000 0000') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div> {{-- END CONTENT --}}
</body>
</html>
