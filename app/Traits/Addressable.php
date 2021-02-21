<?php

namespace App\Traits;

trait Addressable
{
    /**
     * Returns the full address of the individual renewal.
     *
     * @return string
     **/
    public function getFullAddress()
    {
        return $this->address_line_1 . ' ' . $this->address_line_2;
    }
}
