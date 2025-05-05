<?php

return [
    "menu" => [
        [
            "title" => "Data Master",
            "icon" => "<i class='fas fa-database'></i>",
            "sub_menu" => [
                [
                    "title" => "Barang",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Pegawai",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Icd 10",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Nakes",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Pasien",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Supplier",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Tarif",
                    "method" => ["Index", "Form"],
                ],
            ]
        ],
        // [
        //     "title" => "Rekapitulasi Stok",
        //     "method" => ["Index"],
        //     "icon" => "<i class='fa fa-legal'></i>",
        // ],
        // [
        //     "title" => "Laporan",
        //     "icon" => "<i class='fa fa-file-text'></i>",
        //     "sub_menu" => [
        //         [
        //             "title" => "Laba Rugi",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "Konsinyasi",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "Jasa Pelayanan",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "Pengadaan",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "Pengeluaran",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "LHK",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "Pengeluaran Gaji",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "Penerimaan",
        //             "sub_menu" => [
        //                 [
        //                     "title" => "Apotek",
        //                     "method" => ["Index"],
        //                 ],
        //                 [
        //                     "title" => "Klinik",
        //                     "method" => ["Index"],
        //                 ]
        //             ]
        //         ],
        //         [
        //             "title" => "Stok Barang",
        //             "method" => ["Index"],
        //         ]
        //     ]
        // ],
        // [
        //     "title" => "Manajemen Stok",
        //     "icon" => "<i class='fas fa-cubes'></i>",
        //     "sub_menu" => [
        //         [
        //             "title" => "Pengadaan Konsinyasi",
        //             "method" => ["Index", "Form"],
        //         ],
        //         [
        //             "title" => "Pengadaan",
        //             "method" => ["Index", "Form"],
        //         ],
        //         [
        //             "title" => "Barang Masuk",
        //             "method" => ["Index", "Form"],
        //         ]
        //     ]
        // ],
        // [
        //     "title" => "Penjualan",
        //     "icon" => "<i class='fas fa-cash-register'></i>",
        //     "sub_menu" => [
        //         [
        //             "title" => "Data",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "Bebas",
        //             "method" => ["Index"],
        //         ],
        //         [
        //             "title" => "Resep",
        //             "method" => ["Index"],
        //         ]
        //     ]
        // ],
        // [
        //     "icon" => "<i class='fas fa-info-circle'></i>",
        //     "title" => "Informasi Harga",
        //     "method" => ["Index"],
        // ],
        // [
        //     "icon" => "<i class='fas fa-users'></i>",
        //     "title" => "Informasi Pasien",
        //     "method" => ["Index"],
        // ],
        // [
        //     "icon" => "<i class='fas fa-dollar'></i>",
        //     "title" => "Gaji",
        //     "method" => ["Index", "Form"],
        // ],
        // [
        //     "icon" => "<i class='fas fa-dollar'></i>",
        //     "title" => "Pengeluaran",
        //     "method" => ["Index", "Form"],
        // ],
        // [
        //     "icon" => "<i class='fas fa-dollar'></i>",
        //     "title" => "Pelunasan Pengadaan",
        //     "method" => ["Index", "Form"],
        // ],
        [
            "title" => "Pelayanan",
            "icon" => "<i class='fas fa-stethoscope'></i>",
            "sub_menu" => [
                [
                    "title" => "Pendaftaran",
                    "method" => ["Index", "Data"],
                ],
                [
                    "title" => "Pemeriksaan Awal",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Diagnosis",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Tindakan",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Kasir",
                    "method" => ["Index", "Form"],
                ],
                [
                    "title" => "Catatan Pasien",
                    "method" => ["Index", "Form"],
                ]
            ]
        ],
        [
            "title" => "Hak Akses",
            "icon" => "<i class='fa fa-cog'></i>",
            "method" => ["Index", "Form"],
        ],
    ]
];
