<?php

namespace App\Traits;

use Illuminate\Support\Facades\URL;

trait TerminologyTrait
{
    public function getTerminology($url)
    {
        return json_decode(file_get_contents(URL::to($url)), true)['concept'];
    }
}
