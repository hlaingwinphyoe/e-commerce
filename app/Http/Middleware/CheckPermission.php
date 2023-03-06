<?php

namespace App\Http\Middleware;

use App\Models\Item;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,...$permissions)
    {
        $url = url()->current();

        $user_count =  User::whereHas('role', function ($q) {
            $q->where('type', 'Operation');
        })->count();

        if (!$request->user()->role->hasPermissions($permissions)) {
            abort(403, "You Don't Have Permission To Access On This Server.");
        } elseif ($url == config('app.url') . '/admin/items/create' && Item::withTrashed()->count() >= config('app.max_data')) {
            abort(403, "Your data reached over limit " . config('app.max_data'));
        } elseif ($url == config('app.url') . '/admin/users/create' && $user_count >= config('app.max_user')) {
            abort(403, "Your account limit is full.");
        }
        return $next($request);
    }
}
