<?php

arch('Request validation classes must be final')
    ->expect('App\Http\Requests')
    ->classes()
    ->toBeFinal();

arch ('The code in the app directory has strict_types declared')
    ->expect('App')
    ->toUseStrictTypes();
