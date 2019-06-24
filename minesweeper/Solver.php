<?php

class Solver
{
    /**
     * Solve question
     *
     * @param string $input
     * @return string
     */
    public function __invoke()
    {
        $output = '';

        $inMine = false;
        $fieldCnt = 0;
        $n = 0;
        $m = 0;
        $row = 0;
        $result = [];
        while ($line = fgets(STDIN)) {
            $columns = str_split(trim($line));

            // Get n & m
            if (!$inMine) {
                if (3 == count($columns)) { // "n m" => [n, ' ', m]
                    $n = $columns[0];
                    $m = $columns[2];

                    if (0 == $n && 0 == $m) { // cannot use ===
                        return trim($output) . "\n";
                    }

                    $inMine = true;
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

                // all the 8 cells adjacent to the mine will be incremented by 1
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
                $inMine = false; // for next iteration

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
