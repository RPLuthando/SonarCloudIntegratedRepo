<?php

namespace App\Http\Middleware;

use Closure;

class admin
{ 
    public function handle($request, Closure $next)
    {          
        if(auth()->check()){
            if(check_role()==1){
                return $next($request); 
            }else{
                return redirect()->back()->with('error_message','Not Authorized!!');
            }
        }else{
            return redirect('/login-site');
        }
       
    }
}
