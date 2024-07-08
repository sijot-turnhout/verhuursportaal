<div class="card card-reservation">
    <div class="card-header card-header-reservation border-bottom-0">
        <h3>Overtuigd?! Reserveer dan hier!</h3>
    </div>

    <form method="POST" id="reservationForm" action="{{ route('booking.store') }}" class="card-body card-body-reservation">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-2 pe-2">
                <label for="aankomst" class="form-label">Aankomst <span class="fw-bold text-danger">*</span></label>
                <input type="date" name="aankomst" value="{{ old('aankomst') }}" class="form-control form-control-sm @error('aankomst') is-invalid @enderror" id="aankomst">
                <x-forms.validation-error field="aankomst"/>
            </div>

            <div class="col-md-6 mb-2">
                <label for="vertrek" class="form-label">Vertrek <span class="fw-bold text-danger">*</span></label>
                <input type="date" name="vertrek" value="{{ old('vertrek') }}" class="form-control form-control-sm @error('vertrek') is-invalid @enderror" id="vertrek">
                <x-forms.validation-error field="vertrek"/>
            </div>

            <div class="col-md-7 pe-2">
                <label for="groep" class="form-label">Groep <span class="fw-bold text-danger">*</span></label>
                <input type="text" name="groep" value="{{ old('groep') }}" class="form-control form-control-sm @error('groep') is-invalid @enderror" id="groep">
                <x-forms.validation-error field="groep"/>
            </div>

            <div class="col-md-5">
                <label for="aantal_personen" class="form-label">Aantal personen <span class="fw-bold text-danger">*</span></label>
                <input type="number" name="aantal_personen" value="{{ old('aantal_personen') }}" class="form-control form-control-sm @error('aantal_personen') is-invalid @enderror" id="aantal_personen">
                <x-forms.validation-error field="aantal_personen"/>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-5 mb-2 pe-2">
                <label for="voornaam" class="form-label">Voornaam <span class="fw-bold text-danger">*</span></label>
                <input type="text" value="{{ old('voornaam') }}" name="voornaam" class="form-control @error('voornaam') is-invalid @enderror form-control-sm" id="voornaam">
                <x-forms.validation-error field="voornaam"/>
            </div>

            <div class="col-md-7 mb-2">
                <label for="achternaam" class="form-label">Achternaam <span class="fw-bold text-danger">*</span></label>
                <input type="text" name="achternaam" value="{{ old('achternaam') }}" class="form-control form-control-sm @error('achternaam') is-invalid @enderror" id="achternaam">
                <x-forms.validation-error field="achternaam"/>
            </div>

            <div class="col-md-12 mb-3">
                <label for="email" class="form-label">Email adres <span class="fw-bold text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email">
                <x-forms.validation-error field="email"/>
            </div>

            <div class="col-md-12 mb-3">
                <label for="tel_nummer" class="form-label">Tel. nr</label>
                <input type="text" name="telefoon_nummer" value="{{ old('telefoon_nummer') }}" class="form-control form-control-sm" id="tel_nummer">
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" name="offerte_aanvraag" @checked(old('offerte_aanvraag')) type="checkbox" value="1" id="flexCheckChecked">
                    <label class="form-check-label" for="flexCheckChecked">
                        Ik wens eerst een offerte te ontvangen
                    </label>
                </div>
            </div>
        </div>
    </form>

    <div class="card-footer card-footer-reservation border-top-0">
        <button type="submit" form="reservationForm" class="btn btn-submit">
            <x-heroicon-o-paper-airplane class="icon me-1"/> Reserveren
        </button>
    </div>
</div>
