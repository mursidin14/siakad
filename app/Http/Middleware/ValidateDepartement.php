<?php

namespace App\Http\Middleware;

use App\Models\Departement;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateDepartement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $departement = Departement::query()
        ->where('id', $request->departement_id)
        ->where('faculty_id', $request->departement_id)
        ->exists();

        if(!$departement) {
            flashMessage('Program studi tidak ada di fakultas yang ada', 'error');
            return back();
        }

        return $next($request);
    }
}
