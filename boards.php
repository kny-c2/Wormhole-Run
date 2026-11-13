
<?php

/*
 * Wormholes move players forward.
 * Black holes move players backward.
 * Event cells trigger random AI events (will add later)
 */

$boards = [

    // BEGINNER BOARD

    'beginner' => [

        'wormholes' => [
            4  => 14,
            12 => 24,
            22 => 38,
            35 => 49,
            48 => 63,
            60 => 76,
            72 => 88,
            81 => 96
        ],

        'black_holes' => [
            17 => 7,
            32 => 19,
            46 => 29,
            59 => 42,
            74 => 55,
            89 => 68
        ],

        'events' => [
            9, 27, 41, 56, 69, 84, 93
        ]
    ],


    // STANDARD BOARD

    'standard' => [

        'wormholes' => [
            3  => 18,
            16 => 31,
            28 => 45,
            39 => 57,
            51 => 70,
            64 => 82,
            77 => 91
        ],

        'black_holes' => [
            21 => 8,
            36 => 20,
            49 => 33,
            62 => 44,
            75 => 58,
            87 => 66,
            97 => 79
        ],

        'events' => [
            11, 25, 42, 54, 68, 80, 94
        ]
    ],

    // EXPERT BOARD

    'expert' => [

        'wormholes' => [
            8  => 27,
            19 => 41,
            33 => 54,
            47 => 69,
            58 => 79,
            73 => 92
        ],

        'black_holes' => [
            15 => 3,
            29 => 12,
            43 => 22,
            56 => 34,
            67 => 46,
            82 => 61,
            95 => 74,
            99 => 85
        ],

        'events' => [
            6, 24, 38, 52, 65, 78, 90
        ]
    ]

];

?>
