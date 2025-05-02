<?php

namespace App\Enums;

enum OfficeEnum: String
{
    //
    case Apotek = "Apotek";
    case Klinik = "Klinik";

    public function label(): string
    {
        return match ($this) {
            self::Apotek => 'Apotek',
            self::Klinik => 'Klinik',
        };
    }
}
