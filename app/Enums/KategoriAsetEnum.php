<?php

namespace App\Enums;

enum KategoriAsetEnum: String
{
    //
    
    case Bangunan = "Bangunan";
    case PeralatanLainnya = "Peralatan Lainnya";
    case MesinMesin = "Mesin Mesin";
    case Perabot = "Perabot";
    case Kendaraan = "Kendaraan";
    case Elektronik = "Elektronik";
    case AlatMedis = "Alat Medis";

    public function label(): string
    {
        return match ($this) {
            self::Bangunan => 'Bangunan',
            self::PeralatanLainnya => 'Peralatan Lainnya',
            self::MesinMesin => 'Mesin Mesin',
            self::Perabot => 'Perabot',
            self::Kendaraan => 'Kendaraan',
            self::Elektronik => 'Elektronik',
            self::AlatMedis => 'Alat Medis',
        };
    }
}
