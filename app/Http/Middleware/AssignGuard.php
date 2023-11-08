<?php

namespace App\Http\Middleware;
use App\Traits\GeneralTrait;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use JWTAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignGuard extends BaseMiddleware
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( $request, Closure $next, $guard = null)
    {
        if($guard != null)

            auth()->shouldUse($guard); //shoud you user guard / table
            $token = $request->header('auth-token');
            $request->headers->set('auth-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer'.$token, true);

            try{
                // $user = $this->auth->authenticate($request);  //check authonticated user
                 $user = JWTAuth::parseToken()->authenticate();
            }catch(TokenExpiredException $e){
                return  $this -> returnError('401','Unauthenticated user');
            }
            catch (JWTException $e) {

                return  $this -> returnError('1', 'token_invalid '.$e->getMessage());
            }

        return $next($request);


    }
}
