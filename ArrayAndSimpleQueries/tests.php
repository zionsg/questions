<?php

require '../src/Tester.php';

$tester = new Tester();
echo $tester->runTestSuite([
    [   // Should not have leading spaces for the lines
        'Test 01',
        "
8 4
1 2 3 4 5 6 7 8
1 2 4
2 3 5
1 4 7
2 1 4
        ",
        "
1
2 3 6 5 7 8 4 1
        ",
    ],

    [   // Should not have leading spaces for the lines
        'Test 02',
        "
8 2
2 3 4 1 5 6 7 8
1 3 5
1 2 2
        ",
        "
7
1 4 5 2 3 6 7 8
        ",
    ],
]);
