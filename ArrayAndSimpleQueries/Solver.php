<?php

/**
 * Solver for Arrays and Simple Queries
 *
 * Algorithm
 *   - Calculate offsets for original elements, i.e. offset of 2nd element from 1st, 3rd element from 2nd, etc.
 *   - Offset array is one-based, to correspond to one-based positions of elements.
 *   - Query syntax: type start end.
 *   - Query type 1: Move query block to front of elements.
 *       + Close gap, i.e. compute new offset for position after [end] before moving query block.
 *       + Compute new offset for position after [end] after moving block.
 *       + Compute new offset for position 1.
 *       + Maintain offsets of block sitting between old and new positions of query block.
 *       + Maintain offsets within query block.
 *   - Query type 2: Move query block to rear of elements.
 *       + Close gap, i.e. compute new offset for position at [start] before moving query block.
 *       + Compute new offset for position at [start] after moving block.
 *       + Maintain offsets of block sitting between old and new positions of query block.
 *       + Maintain offsets within query block.
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

                    $isStarted = true;
                    $queryCnt = 0;
                    continue;
                }
            }

            // Reaching this point means we have started with the queries
            $queryCnt++;
            [$type, $start, $end] = $columns;
            $updates = $this->runQuery($type, $start, $end);

            // Apply updates
            foreach ($updates as $pos => $value) {
                $this->offsets[$pos] = $value;
            }

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
     * @param int $type
     * @param int $start
     * @param int $end
     * @return array Updated offsets indexed by position
     */
    protected function runQuery($type, $start, $end)
    {
        $updates = [];

        // Move to front
        if (1 == $type) { // move to front
            if (1 == $start) {
                return []; // already in front
            }

            $blockLength = $end - $start + 1;

            // close gap - update offset [end + 1]
            if ($end < $this->n) {
                $pos = $end + 1;
                $value = 0;
                for ($i = $start; $i <= ($end + 1); $i++) {
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

            // maintain offsets in block before query block before moving
            for ($i = 2; $i <= ($start -1); $i++) {
                $pos = $i + $blockLength;
                $updates[$pos] = $this->offsets[$i];
            }

            // maintain offsets in query block to be moved
            for ($i = ($start + 1); $i <= $end; $i++) {
                $pos = $i - $start + 1;
                $updates[$pos] = $this->offsets[$i];
            }

            return $updates;
        }

        // Move to rear
        if (2 == $type) {
            if ($end == $this->n) {
                return []; // alr at the rear
            }

            $blockLength = $end - $start + 1;

            // close gap - update offset[start]
            $pos = $start;
            $value = 0;
            for ($i = $start; $i <= ($end + 1); $i++) {
                $value += $this->offsets[$i] ?? 0;
            }
            $updates[$pos] = $value;

            // update offset [n - blockLength], the new start position of the query block
            $pos = $this->n - $blockLength + 1;
            $value = 0;
            for ($i = ($start + 1); $i <= $this->n; $i++) {
                $value -= $this->offsets[$i] ?? 0;
            }
            $updates[$pos] = $value;

            // maintain offsets in block after query block before moving
            for ($i = ($end + 2); $i <= $this->n; $i++) {
                $pos = $i - $blockLength;
                $updates[$pos] = $this->offsets[$i];
            }

            // maintain offsets in query block to be moved
            for ($i = ($start + 1); $i <= $end; $i++) { // don't touch [start] cos already modified
                $pos = $i + $blockLength;
                $updates[$pos] = $this->offsets[$i];
            }

            return $updates;
        }

        return $updates;
    }

    /**
     * Print array with consistent spacing, catering for negative numbers. For debugging purposes.
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
