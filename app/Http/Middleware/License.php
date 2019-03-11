<?php

namespace App\Http\Middleware;

use Route;
use Storage;
use Closure;
use Licensor;

class License
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'install',
        'verify-purchase',
        'verify-purchase-post',
        
        'LaravelInstaller::welcome',
        'LaravelInstaller::database',
        'LaravelInstaller::environment',
        'LaravelInstaller::environmentClassic',
        'LaravelInstaller::environmentSaveClassic',
        'LaravelInstaller::environmentSaveWizard',
        'LaravelInstaller::environmentWizard',
        'LaravelInstaller::final',
        'LaravelInstaller::permissions',
        'LaravelInstaller::requirements',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currentRoute = Route::currentRouteName();

        if (in_array($currentRoute, $this->except)) {
            return $next($request);
        }
        
        $verifyPurchase = Licensor::verifyPurchase();
        
        
        if (!$verifyPurchase) {
            return redirect()->route('verify-purchase');
        }

        Licensor::verifyLicense();

        return $next($request);
    }

}
