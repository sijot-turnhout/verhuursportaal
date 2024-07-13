<?php

arch('Request validation classes must be final')
    ->expect('App\Http\Requests')
    ->classes()
    ->toBeFinal();
