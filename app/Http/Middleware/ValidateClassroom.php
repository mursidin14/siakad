<?php

namespace App\Http\Middleware;

use App\Models\ClassRoom;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateClassroom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $classroom = ClassRoom::query()
        ->where('id', $request->class_room_id)
        ->where('faculty_id', $request->faculty_id)
        ->where('departement_id', $request->departement_id)
        ->exists();

        if(!$classroom) {
            flashMessage('Kelas tidak ada di program studi atau fakultas yang ada', 'error');
            return to_route('admin.schedules.index');
        }

        return $next($request);
    }
}
