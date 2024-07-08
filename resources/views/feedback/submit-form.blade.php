<x-layouts.main>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-brown">Deel je feedback</h4>
                        <p class="card-text mt-4">
                            Bedankt om de tijd te nemen om ons te informeren omtrent je ideen, problemen en of waardering tijdens je verhuring bij ons.
                            We kunnen niet invidueel op elke feedback reageren. Maar bekijken ze wel en zorgen ervoor dat de feedback bij de juiste persoon terecht komt.
                            En we onze diensten en of domein kunnen verbeteren naar de toekomst.
                        </p>

                        <hr>

                        <form method="POST" id="feedbackForm">
                            @csrf {{-- Form field protection --}}

                            <div class="mb-3">
                                <label for="subject" class="form-label">Onderwerp <span class="fw-bold text-danger">*</span></label>
                                <input type="text" class="form-control @error('onderwerp') is-invalid @enderror" id="subject" placeholder="Bv. communicatie" name="onderwerp" value="{{ old('onderwerp') }}">

                                @error('onderwerp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="feedback" class="form-label">Feedback <span class="fw-bold text-danger">*</span></label>
                                <textarea name="feedback" class="form-control @error('feedback') is-invalid @enderror" id="feedback" rows="7">{{ old('feedback') }}</textarea>

                                @error('feedback')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="card-footer border-top-0">
                        <button type="submit" form="feedbackForm" class="btn btn-request">
                            <x-heroicon-o-paper-airplane class="icon me-1"/> {{ __('insturen') }}
                        </button>

                        <button type="reset" form="feedbackForm" class="btn btn-link">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
