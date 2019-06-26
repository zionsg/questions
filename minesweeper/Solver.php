<?php

/**
 * Minesweeper solver
 *
 * Algorithm:
 *   - Use a one-based array to record mines and hints for each minefield.
 *   - When a mine is hit, increment the surrounding 8 cells by 1. If a surrounding cell contains a mine, leave it.
 *   - When printing out the minefield, ignore indices 0 and (m + 1) in the array.
 */
class Solver
{
    /**
     * Solve question
     *
     * @return string
     */
    public function __invoke()
    {
        $output = '';

        $inMinefield = false;
        $fieldCnt = 0;
        $n = 0; // no. of lines in minefield
        $m = 0; // no. of columns per line in minefield
        $row = 0;
        $result = [];
        while ($line = fgets(STDIN)) {
            $columns = str_split(trim($line));

            // Get n & m
            if (!$inMinefield) {
                if (3 == count($columns)) { // "n m" => [n, ' ', m]
                    $n = $columns[0];
                    $m = $columns[2];

                    if (0 == $n && 0 == $m) { // cannot use ===
                        return trim($output) . "\n";
                    }

                    $inMinefield = true;
                    $fieldCnt++;
                    $row = 0;
                    $result = [];
                    $output .= "Field #{$fieldCnt}:\n";
                    continue;
                }
            }

            // Reaching this point means we are in the field, starting from row 1
            $row++;
            for ($col = 1; $col <= $m; $col++) {
                $column = $columns[$col - 1];
                if ($column !== '*') {
                    continue;
                }

                // all the 8 cells surrounding the mine will be incremented by 1
                $result[$row - 1][$col - 1] = $this->inc($result[$row - 1][$col - 1] ?? 0); // top diagonal left
                $result[$row - 1][$col]     = $this->inc($result[$row - 1][$col] ?? 0); // top center
                $result[$row - 1][$col + 1] = $this->inc($result[$row - 1][$col + 1] ?? 0); // top diagonal right
                $result[$row][$col - 1]     = $this->inc($result[$row][$col - 1] ?? 0); // left
                $result[$row][$col]         = '*'; // the cell with the mine
                $result[$row][$col + 1]     = $this->inc($result[$row][$col + 1] ?? 0); // right
                $result[$row + 1][$col - 1] = $this->inc($result[$row + 1][$col - 1] ?? 0); // bottom diagonal left
                $result[$row + 1][$col]     = $this->inc($result[$row + 1][$col] ?? 0); // bottom center
                $result[$row + 1][$col + 1] = $this->inc($result[$row + 1][$col + 1] ?? 0); // bottom diagonal right
            }

            // When all rows in minefield covered, print out minefield with hints
            if ($n == $row) { // cannot use ===
                $inMinefield = false; // for next iteration

                for ($i = 1; $i <= ($n * $m); $i++) {
                    $row = ceil($i / $m);
                    $col = $i % $m + (0 == $i % $m ? $m : 0); // if col = 0, set col = m
                    $output .= $result[$row][$col] ?? 0;

                    if ($m == $col) {
                        $output .= "\n";
                    }
                }

                $output .= "\n";
            }
        }

        return $output;
    }

    /**
     * Return incremented value for cell
     *
     * If the cell contains a '*', return '*' instead of +1.
     *
     * @param mixed $value
     * @return mixed
     */
    protected function inc($value)
    {
        return ('*' === $value) ? $value : ($value + 1);
    }
}
