<?php

namespace App\Enums;

enum PositionEnum: String
{
        //

    case Direktur = "Direktur";
    case Supervisor = "Supervisor";
    case Admin = "Admin";
    case Perawat = "Perawat";
    case Apoteker = "Apoteker";
    case AsistenApoteker = "Asisten Apoteker";
    case PenanggungJawab = "Penanggung Jawab";

    public function label(): string
    {
        return match ($this) {
            self::Direktur => 'Direktur',
            self::Supervisor => 'Supervisor',
            self::Admin => 'Admin',
            self::Perawat => 'Perawat',
            self::Apoteker => 'Apoteker',
            self::AsistenApoteker => 'Asisten Apoteker',
            self::PenanggungJawab => 'Penanggung Jawab',
        };
    }
}
