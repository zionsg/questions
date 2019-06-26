<?php

class Tester
{
    const INPUT_FILENAME = 'input.txt';
    const RUN_SCRIPT = 'run.php';

    /**
     * Run test suite of tests.
     *
     * @param array $testSuite Array of tests - @see runTest() for structure of a test.
     * @param bool $trimOutput Default=true. Whether to trim expected output and actual output before comparing.
     * @return string
     */
    public function runTestSuite(array $testSuite, $trimOutput = true)
    {
        $output = '';

        foreach ($testSuite as $test) {
            $output .= $this->runTest($test, $trimOutput);
        }

        return $output;
    }

    /**
     * Run a test.
     *
     * @param array $test Array of 3 strings: [<name of test>, <input for test>, <expected output for test>].
     * @param bool $trimOutput Default=true. Whether to trim expected output and actual output before comparing.
     * @return string
     */
    public function runTest(array $test, $trimOutput = true)
    {
        $output = '';
        [$name, $input, $expectedOutput] = $test;

        file_put_contents(self::INPUT_FILENAME, trim($input));
        $actualOutput = shell_exec(sprintf('php %s < %s', self::RUN_SCRIPT, self::INPUT_FILENAME));

        if ($trimOutput) {
            $actualOutput = trim($actualOutput);
            $expectedOutput = trim($expectedOutput);
        }

        $passed = ($actualOutput == $expectedOutput);
        $output .= sprintf(
            '%s: Test "%s"' . "\n",
            $passed ? 'passed' : 'FAILED',
            $name
        );

        if (!$passed) {
            $output .= "Expected output:-----\n{$expectedOutput}\n-----\n\n";
            $output .= "Actual output:-----\n{$actualOutput}\n-----\n\n";
        }

        return $output;
    }
}
