<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * WelcomseNewFeedback Middleware Class
 *
 * This middleware validates incoming requests for new feedback submissions.
 * It checks if the request has a valid signature, if the associated lease exists,
 * if the feedback submission is still valid, and if it has not been previously used.
 * If any of these conditions fail, it aborts the request with an appropriate error response.
 */
final class WelcomseNewFeedback
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request The incoming HTTP request.
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next  The next middleware or request handler.
     * @return Response Returns a Symfony Response instance.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( ! $request->hasValidSignature()) {
            abort(Response::HTTP_FORBIDDEN, trans('De feedback link is ongeldig of inmiddels vervallen.'));
        }

        if ( ! $request->lease) {
            abort(Response::HTTP_FORBIDDEN, trans('Kan de verhuring niet vinden in het systeem'));
        }

        if (null === $request->lease->feedback_valid_until) {
            abort(Response::HTTP_FORBIDDEN, trans('De feedback is reeds gebruikt'));
        }

        if (Carbon::create($request->lease->feedback_valid_until)->isPast()) {
            abort(Response::HTTP_FORBIDDEN, trans('De feedback link is niet meer geldig'));
        }

        return $next($request);
    }
}
