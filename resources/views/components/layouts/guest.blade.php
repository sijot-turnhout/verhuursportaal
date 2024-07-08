<x-layouts.main>
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="5" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="6" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 450px !important">
                <img src="https://placehold.co/600x400/000000/FFF" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Terrein</h5>
                </div>
            </div>

            <div class="carousel-item" style="height: 450px !important">
                <img src="https://placehold.co/600x400/000000/FFF" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Sanitaire blok</h5>
                </div>
            </div>

            <div class="carousel-item" style="height: 450px !important">
                <img src="https://placehold.co/600x400/000000/FFF" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Keukenl</h5>
                </div>
            </div>

            <div class="carousel-item" style="height: 450px !important">
                <img src="https://placehold.co/600x400/000000/FFF" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Kapoenen + welpen lokaal</h5>
                </div>
            </div>

            <div class="carousel-item" style="height: 450px !important">
                <img src="https://placehold.co/600x400/000000/FFF" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Grote zaal</h5>
                </div>
            </div>

            <div class="carousel-item" style="height: 450px !important">
                <img src="https://placehold.co/600x400/000000/FFF" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Jong-gover lokaal</h5>
                </div>
            </div>

            <div class="carousel-item" style="height: 450px !important">
                <img src="https://placehold.co/600x400/000000/FFF" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Jin Lokaal</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="container pt-4">

    {{--  Page title --}}
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
            <div class="col-8 pe-5">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ active('welcome') }}" href="{{ route('welcome') }}" aria-selected="true" role="tab">Algemene informatie</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ active('price-information') }}" href="{{ route('price-information') }}">Wat kost dat?!</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ active('offerte.information') }}" href="{{ route('offerte.information') }}">Offerte aanvragen</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ active('availability') }}" href="{{ route('availability') }}">Beschikbaarheid</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="behandeling">Behandelingsprocedure</a>
                    </li>
                </ul>

                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active show" role="tabpanel">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <div class="col-4 pt-5">
                <x-reservation-form/>
            </div>
        </div>

    {{-- Need to knows --}}
        <div class="row">
            <hr class="mt-2">

            <div class="col-12">
                <h4 class="pb-2">Need to knows</h4>
            </div>

            <div class="col-4 pb-3">
                <div class="card h-100 bg-need-to-know card-body">
                    <h4 class="card-title fw-bold">Verbruik</h4>
                    <p class="card-text">Het verbruik wordt apart verrekend aan de geldende water- en energieprijzen</p>
                </div>
            </div>

            <div class="col-4 pb-3 px-3">
                <div class="card h-100 bg-need-to-know card-body">
                    <h4 class="card-title fw-bold">Afval</h4>
                    <p class="card-text">Afval kan bij ons worden achtergelaten: €5 euro per zak restafval, €0,20 per PMD-zak</p>
                </div>
            </div>

            <div class="col-4 pb-3">
                <div class="card h-100 bg-need-to-know card-body">
                    <h4 class="card-title fw-bold">Keuken</h4>

                    <p class="card-text">Er is kookmateriaal, borden en bestek aanwezig voor maar liefst 100 personen. De keuken voorziet 6 kleine en 2 grote gasvuren. Er is geen oven.</p>
                </div>
            </div>

            <div class="col-4 pb-3">
                <div class="card h-100 bg-need-to-know card-body">
                    <h4 class="card-title fw-bold">Tafels en stoelen</h4>
                    <p class="card-text">Er zijn 10 tafels en 20 banken voorzien. Daarnaast zijn er ook nog eens heel wat stoelen.</p>
                </div>
            </div>

            <div class="col-4 pb-3 px-3">
                <div class="card h-100 bg-need-to-know card-body">
                    <h4 class="card-title fw-bold">Waarbord</h4>
                    <p class="card-text">De waarborg dient vooraf betaald te worden. Het verbruik wordt afgetrokken van de waarborg. De resterende rekening kan je gewoon met de kaart betalen op het einde van je verblijf.</p>
                </div>
            </div>

            <div class="col-4 pb-3">
                <div class="card h-100 bg-need-to-know card-body">
                    <h4 class="card-title fw-bold">Parking</h4>
                    <p class="card-text">Er is een ruime gratis parking vooraan op het terrein.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <hr class="mt-2 mb-3">

            <div class="col-12">
                <h4 class="pb-1">
                    <span class="text-brown">Hadde een vraag?!</span>
                    <span class="callout-text">Aarzel dan niet om ons te contacteren.</span>
                </h4>
            </div>

           <x-contact-form/>

        </div>
    </div>
</x-layouts.main>
