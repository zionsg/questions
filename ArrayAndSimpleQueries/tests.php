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

    [
        'Test 02',
        "
20 20
20000 14207 12040 26476 29558 14636 20025 12657 25055 16071 10278 19381 25145 31369 16306 8410 6828 1990 22897 24439
2 5 11
1 8 13
2 9 13
1 6 11
2 2 10
1 1 3
2 1 3
1 3 7
2 6 14
1 7 10
2 4 6
1 7 14
2 2 11
1 6 10
2 4 6
1 9 12
2 4 11
1 2 8
2 7 10
1 8 9
        ",
        "
1868
8410 20000 16071 31369 12657 14207 29558 6828 12040 20025 16306 22897 26476 19381 25145 14636 1990 25055 24439 10278
        "
    ],
]);
