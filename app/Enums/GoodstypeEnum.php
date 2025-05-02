<?php

namespace App\Enums;

enum GoodstypeEnum: String
{
    //
    case Obat = "Obat";
    case AlatKesehatan = "Alat Kesehatan";

    public function label(): string
    {
        return match ($this) {
            self::Obat => 'Obat',
            self::AlatKesehatan => 'Alat Kesehatan',
        };
    }
}
