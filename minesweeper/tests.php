<?php
include 'Solver.php';

// Should not have leading spaces for the lines
$tests = [
    [
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
];

$solver = new Solver();
foreach ($tests as $test) {
    [$name, $input, $expectedOutput] = $test;

    $expectedOutput = trim($expectedOutput);
    $actualOutput = trim($solver($input));
    $passed = ($actualOutput == $expectedOutput);

    printf(
        'Test "%s": %s' . "\n",
        $name,
        $passed ? 'passed' : 'FAILED'
    );

    if (!$passed) {
        echo "Expected output:-----\n{$expectedOutput}\n-----\n\n";
        echo "Actual output:-----\n{$actualOutput}\n-----\n\n";
    }
}
