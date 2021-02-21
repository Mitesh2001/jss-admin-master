<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class SecurityException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        Log::channel('security')
            ->info('User tried to renew with invalid data', ['request_data' => request()->all()])
        ;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return redirect()->back()->withInput();
    }
}
