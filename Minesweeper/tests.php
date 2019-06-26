<?php

require '../src/Tester.php';

$tester = new Tester();
echo $tester->runTestSuite([
    [   // Should not have leading spaces for the lines
        'Test 01',
        "
4 4
*...
....
.*..
....
3 5
**...
.....
.*...
0 0
        ",
        "
Field #1:
*100
2210
1*10
1110

Field #2:
**100
33200
1*100
        ",
    ],

]);
