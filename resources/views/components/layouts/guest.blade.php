<x-layouts.main>
    <section="header">
        <img src="{{ asset('img/domein/terrein.jpg') }}" height="600" width="100%" alt="">
    </section>

    <div class="meh">
        <div class="pattern-layer"></div>
    </div>


    {{--  Page title --}}
        <section style="background-color: #fff;">
            <div class="container pt-4">
            <div class="row">
                <div class="col-12 mt-3">
                    <h3 class="mb-0">
                        <span class="callout-text">Geheel domein van het jeugdlokaal Het groen,</span>
                        <span class="text-brown">Turnhout</span>
                    </h3>
                    <ul class="list-inline text-muted">
                        <li class="list-inline-item"><x-heroicon-o-users class="icon icon-page-title me-1"/> max. 450 personen</li>
                        <li class="list-inline-item"><x-heroicon-o-home-modern class="icon icon-page-title me-1"/> 7 Lokalen</li>
                        <li class="list-inline-item"><x-heroicon-o-fire class="icon icon-fireplace me-1"/> 1 vuurkring</li>
                    </ul>
                </div>

                @if (flash()->message)
                    <div class="col-12">
                        <div class="alert {{ flash()->class }} shadow-sm mb-0 alert-dismissible fade show" role="alert">
                            <span class="text-success">{{ flash()->message }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
            </div>

        {{-- Information + signup form --}}
            <div class="row mb-3 mt-3">
                <div class="col-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ active('welcome') }}" href="{{ route('welcome') }}" data-pan="algemene-informatie" aria-selected="true" role="tab">Algemene informatie</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ active('price-information') }}" data-pan="kosten-informatie" href="{{ route('price-information') }}">Wat kost dat?!</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active('offerte.information') }}" data-pan="offerte-informatie" href="{{ route('offerte.information') }}">Offerte aanvragen</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ active('availability') }}" data-pan="beschikbaarheid" href="{{ route('availability') }}">Beschikbaarheid</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="behandeling" data-pan="behandelingsprocedure">Behandelingsprocedure</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-8 pe-5 mt-0">
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active show" role="tabpanel">
                            {{ $slot }}
                        </div>
                    </div>
                </div>

                <div class="col-4 pt-3 mb-3">
                    <x-reservation-form/>
                </div>
            </div>
        </div>
        </section>

    {{-- Need to knows --}}
    <div class="pattern-layer-2"></div>
        <section class="client-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 g-sm-3">
                        <h4 class="pb-2 color-heading">Need to knows</h4>
                    </div>

                    <div class="col-md-4 col-sm-12 pb-3">
                        <div class="card h-100 bg-white card-body">
                            <h4 class="card-title fw-bold">Verbruik</h4>
                            <p class="card-text text-muted">Het verbruik wordt apart verrekend aan de geldende water- en energieprijzen</p>
                        </div>
                    </div>

                    <div class="col-4 pb-4 px-3">
                        <div class="card h-100 bg-white card-body">
                            <h4 class="card-title fw-bold">Afval</h4>
                            <p class="card-text text-muted">Afval kan bij ons worden achtergelaten: €5 euro per zak restafval, €0,20 per PMD-zak</p>
                        </div>
                    </div>

                    <div class="col-4 pb-4">
                        <div class="card h-100 bg-white card-body">
                            <h4 class="card-title fw-bold">Keuken</h4>

                            <p class="card-text text-muted">Er is kookmateriaal, borden en bestek aanwezig voor maar liefst 100 personen. De keuken voorziet 6 kleine en 2 grote gasvuren. Er is geen oven.</p>
                        </div>
                    </div>

                    <div class="col-4 pb-5">
                        <div class="card h-100 bg-white shadow-sm card-body">
                            <h4 class="card-title fw-bold">Tafels en stoelen</h4>
                            <p class="card-text text-muted">Er zijn 10 tafels en 20 banken voorzien. Daarnaast zijn er ook nog eens heel wat stoelen.</p>
                        </div>
                    </div>

                    <div class="col-4 pb-5 px-3">
                        <div class="card h-100 bg-white card-body">
                            <h4 class="card-title fw-bold">Waarbord</h4>
                            <p class="card-text text-muted">De waarborg dient vooraf betaald te worden. Het verbruik wordt afgetrokken van de waarborg. De resterende rekening kan je gewoon met de kaart betalen op het einde van je verblijf.</p>
                        </div>
                    </div>

                    <div class="col-4 pb-5">
                        <div class="card h-100 bg-white card-body">
                            <h4 class="card-title fw-bold">Parking</h4>
                            <p class="card-text text-muted">Er is een ruime gratis parking vooraan op het terrein.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="pattern-layer-3"></div>

        <div class="container">

        <div class="row">
            <div class="col-12">
                <h4 class="pb-1">
                    <span class="text-brown">Nog een vraagske?!</span>
                    <span class="callout-text">Aarzel dan niet om ons te contacteren.</span>
                </h4>
            </div>

           <x-contact-form/>

        </div>
    </div>
</x-layouts.main>
