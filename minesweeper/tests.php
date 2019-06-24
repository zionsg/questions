<?php
$inputFile = 'input.txt';

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

foreach ($tests as $test) {
    [$name, $input, $expectedOutput] = $test;

    file_put_contents($inputFile, trim($input));
    $actualOutput = trim(shell_exec("php run.php < {$inputFile}"));
    $expectedOutput = trim($expectedOutput);
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
