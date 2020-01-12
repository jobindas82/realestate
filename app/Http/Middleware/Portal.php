<?php

namespace App\Http\Middleware;

use Closure;
use App\models\Session;

class Portal
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
        if ($request->session()->has('e')) {
            $encryptedTenant = $request->session()->get('e');
            if( Session::isActive($encryptedTenant) ){
                $request->request->add(['tenantModel' => Session::tenant($encryptedTenant) ]);
                return $next($request);
            }else{
                $request->session()->forget('e');
                $request->session()->flush();
                Session::clear($encryptedTenant);
            }
        }
        return redirect()->route('portal');
    }
}
