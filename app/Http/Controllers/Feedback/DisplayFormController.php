<?php

declare(strict_types=1);

namespace App\Http\Controllers\Feedback;

use App\Models\Lease;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * DisplayFormController class.
 *
 * This controller handles displaying the feedback submission form for a specific lease.
 */
final readonly class DisplayFormController
{
    /**
     * Handle the form display request.
     *
     * This method is invoked when displaying the feedback submission form for a specific lease.
     *
     * @param Request  $request  The HTTP request instance.
     * @param Lease    $lease    The lease instance for which the form is displayed.
     *
     * @return Renderable Returns a Renderable instance representing the view to display.
     */
    public function __invoke(Request $request, Lease $lease): Renderable
    {
        return view('feedback.submit-form', compact('lease'));
    }
}
