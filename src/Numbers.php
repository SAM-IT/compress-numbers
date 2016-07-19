<?php


namespace SamIT\Compress;


class Numbers
{
    /**
     * @var string
     */
    public $factorSeparator = 'X';
    public $entrySeparator = "N";
    public $rangeSeparator = "T";

    /**
     * Compresses an array of numbers, keys and ordering are ignored.
     * @param int[] $numbers
     * @return string A string containing the compressed numbers.
     */
    public function compress(array $numbers)
    {
        sort($numbers);
        $rs = [];
        $r = [];
        $p = $numbers[0] - 1;
        // Step 1: combine consecutive numbers into ranges.
        foreach ($numbers as $c) {
            if (!is_int($c)) {
                throw new \InvalidArgumentException("All elements must be integers.");
            }
            if ($c - 1 === $p) {
                $r[] = $c;
            } else {
                $rs[] = $r;
                $r = [$c];
            }
            $p = $c;
        }
        $rs[] = $r;

        $c = 0;
        $prevC = 0;
        $result = "";
        // Step 2: Prefix set of numbers with a multiplier.
        foreach ($rs as $r) {
            while ($r[0] >= ($c + 1) * 100) {
                $c++;
            }
            if ($c != $prevC) {
                $result .= "$c{$this->factorSeparator}";
            }

            $result .= ($r[0] - $c * 100);
            if (isset($r[1])) {
                $result .= $this->rangeSeparator . (end($r) - $c * 100);
            }
            $result .= $this->entrySeparator;
            $prevC = $c;
        }
        $result = substr($result, 0, -1);

        return $result;
    }

    /**
     *
     * @param string $string The string containing compressed numbers.
     * @return int[] The numbers obtained from decompressing the string.
     */
    public function decompress($string)
    {
        // Expand a again.
        $expanded = [];
        $c = 0;
        foreach(explode($this->entrySeparator, $string) as $range) {
            $parts = explode($this->factorSeparator, $range);
            if (count($parts) === 2) {
                $c = $parts[0];
                $range = $parts[1];
            }
            $parts = explode($this->rangeSeparator , $range);
            $expanded[] = $c * 100 + $parts[0];
            if (count($parts) === 2) {
                for($i = $parts[0] + 1; $i <= $parts[1]; $i++) {
                    $expanded[] = $c * 100 + $i;
                }
            }

        }
        return $expanded;
    }
}