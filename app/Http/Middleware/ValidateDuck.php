<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class ValidateDuck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $duck = \App\Duck::find($request->id);

        if( !empty($duck) ) {
            if($duck->user_id == Auth::id()) {
                // if this is the users duck, we continue
                return $next($request);
            }
        }

        // If this is not the users duck, we display an error to the user
        return response()->json([
            'success' => false,
            'message' => 'This is not your Duck. Didn\'t your mother ever teach to you not touch other peoples things.'
        ]);
    }
}
