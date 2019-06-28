<?php

/**
 * Solver for Arrays and Simple Queries
 */
class Solver
{
    /** @var int Single copy of n & m */
    protected $n = 0; // no. of elements in array
    protected $m = 0; // no. of queries

    /** @var array Single copy of offsets to avoid passing around */
    protected $offsets = [];

    /**
     * Solve question
     *
     * @return string
     */
    public function __invoke()
    {
        $output = '';

        $isStarted = false;
        $queryCnt = 0;

        while ($line = fgets(STDIN)) {
            $columns = explode(' ', trim($line));

            // Get n & m
            if (!$isStarted) {
                if (2 == count($columns)) { // "n m" => [n, m]
                    $this->n = $columns[0];
                    $this->m = $columns[1];

                    // Read the next line to get the elements and calculate offsets
                    $line = fgets(STDIN);
                    $elements = explode(' ', trim($line));
                    $this->offsets = [];
                    for ($i = 1; $i <= $this->n; $i++) {
                        $this->offsets[$i] = $elements[$i - 1] - ($elements[$i - 2] ?? 0);
                    }

/*
echo "n: {$this->n}, m: {$this->m}\n";
echo "Elements:    " . $this->printArray($elements) . "\n";
echo "Old offsets: " . $this->printArray($this->offsets) . "\n";
*/

                    $isStarted = true;
                    $queryCnt = 0;
                    continue;
                }
            }

            // Reaching this point means we have started with the queries
            $queryCnt++;
            [$command, $start, $end] = $columns;
            $updates = $this->runQuery($command, $start, $end);

            // Apply updates
            foreach ($updates as $pos => $value) {
                $this->offsets[$pos] = $value;
            }

        /*
        $value = 0;
        $result = [];
        for ($i = 1; $i <= $this->n; $i++) {
            $value += ($this->offsets[$i] ?? 0);
            $result[] = $value;
        }
        echo "\nCommand: $command $start $end\n";
        echo "Updates: " . json_encode($updates) . "\n";
        echo "New offsets: " . $this->printArray($this->offsets) . "\n";
        echo "New elements:" . $this->printArray($result) . "\n\n";
        */

            if ($queryCnt == $this->m) {
                break;
            }
        } // end while

        // Change offsets to absolute values
        $value = 0;
        $result = [];
        for ($i = 1; $i <= $this->n; $i++) {
            $value += ($this->offsets[$i] ?? 0);
            $result[] = $value;
        }

        $output .= abs($result[0] - $result[$this->n - 1]) . "\n" . implode(' ', $result);

        return $output;
    }

    /**
     * Run query and compute updates to offsets
     *
     * @param int $command
     * @param int $start
     * @param int $end
     * @return array Updated offsets indexed by position
     */
    protected function runQuery($command, $start, $end)
    {
        $updates = [];

        // Move to front
        if (1 == $command) { // move to front
            // close gap - update offset [end + 1]
            if ($end < $this->n) {
                $pos = $end + 1;
                $value = $this->offsets[$pos] ?? 0;
                for ($i = $start; $i <= $end; $i++) {
                    $value += $this->offsets[$i] ?? 0;
                }
                $updates[$pos] = $value;
            }

            // if move 1 char, update offset[2]. If move 2 chars, update offset[3]
            $pos = 1 + ($end - $start + 1);
            $value = 0;
            for ($i = 2; $i <= $end; $i++) {
                $value -= $this->offsets[$i] ?? 0;
            }
            $updates[$pos] = $value;

            // update offset[1]
            $pos = 1;
            $value = $this->offsets[$pos] ?? 0;
            for ($i = 2; $i <= $start; $i++) {
                $value += $this->offsets[$i] ?? 0;
            }
            $updates[$pos] = $value;

            // maintain offsets in block before block to be moved
            $blockLength = $end - $start + 1;
            for ($i = 2; $i <= ($start -1); $i++) {
                $pos = $i + $blockLength;
                $updates[$pos] = $this->offsets[$i];
            }

            // maintain offsets in block to be moved
            for ($i = ($start + 1); $i <= $end; $i++) {
                $pos = $i - $start + 1;
                $updates[$pos] = $this->offsets[$i];
            }

            return $updates;
        }

        // Move to rear
        if (2 == $command) {
            if ($end < $this->n) {
                // close gap - update offset[start]
                $pos = $start;
                $value = $this->offsets[$pos] ?? 0;
                for ($i = ($start + 1); $i <= ($end + 1); $i++) {
                    $value += $this->offsets[$i] ?? 0;
                }
                $updates[$pos] = $value;

                // update offset [n - blockLength]
                $pos = $this->n - ($end - $start);
                $value = 0;
                for ($i = ($start + 1); $i <= $this->n; $i++) {
                    $value -= $this->offsets[$i] ?? 0;
                }
                $updates[$pos] = $value;
            }

            return $updates;
        }

        return $updates;
    }

    /**
     * Print array with consistent spacing, catering for negative numbers
     *
     * @param array $arr
     * @return string
     */
    protected function printArray($arr)
    {
        $output = '';

        foreach ($arr as $val) {
            $output .= str_pad($val, 3, ' ', STR_PAD_LEFT);
        }

        return $output;
    }
}
