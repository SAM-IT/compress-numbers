<?php


namespace SamIT\Compress\Tests;


use SamIT\Compress\Numbers;

class NumbersTest extends \PHPUnit_Framework_TestCase
{
    public function arrayProvider()
    {
        $count = 1000;
        $maxLength = 10000;
        $domainFactor = 10;
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $length = rand(1, $maxLength);
            $arr = [];
            for($j = 0; $j < $length; $j++) {
                $arr[] = rand(1, $domainFactor * $length);
            }
            $result[] = [$arr];

        }
        return $result;
    }
    /**
     *
     * @param $input
     * @dataProvider arrayProvider
     */
    public function testSymmetry(array $input)
    {
        sort($input);
        $compressor = new Numbers();
        $compressed = $compressor->compress($input);
        
        $this->assertEquals($input, $compressor->decompress($compressed));

        $this->assertLessThan(0.4, strlen($compressed) * 1.0 / strlen(serialize($input)), $compressed);
    }
}
