<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $type): Response
{
    // Fetch user_type from the credential table
    $CredentialId = Session::get("credential_id");
    $userType = DB::table('credentials')
        ->where('id', $CredentialId) // Assuming Auth::id() returns the logged-in user's CredentialID
        ->value('user_type');

    if ((int) $userType !== (int) $type) {
        abort(403, 'Unauthorized action.'); // Block if user_type doesn't match
    }

    return $next($request);
}
}
