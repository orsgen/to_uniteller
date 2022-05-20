<?php
require 'complex.php';

use PHPUnit\Framework\TestCase;

class ComplexTest extends TestCase {

    protected   $complex;
    
    protected function setUp(): void
    {
        $this->complex = new Complex();
    }

    protected function tearDown(): void
    {
        $this->complex = NULL;
    }

    /**
     * Test for isvalid()
     * @dataProvider isvalidProvider
     */
    public function testisvalid(array $a, $exp) {
        
        $result = $this->complex->isvalid($a);
        $this->assertSame($exp, $result);
    }

    public function isvalidProvider()
    {
        return [
            [['op'=>'sum', 'x1'=>0, 'y1'=>0, 'x2'=>0, 'y2'=>0], true],
            [['op'=>'diff', 'x1'=>2.5, 'y1'=>1.3, 'x2'=>45, 'y2'=>-18], true],
            [['op'=>'mult', 'x1'=>23, 'y1'=>-8, 'x2'=>-2.6, 'y2'=>0.5], true],
            [['op'=>'div', 'x1'=>17.3, 'y1'=>12, 'x2'=>0, 'y2'=>0], true],
            [['op'=>'', 'x1'=>0, 'y1'=>0, 'x2'=>0, 'y2'=>0], false],
            [['op'=>'mult', 'x1'=>'', 'y1'=>'', 'x2'=>1, 'y2'=>1], false],
            [['op'=>'diff', 'x1'=>0, 'y1'=>0, 'x2'=>'', 'y2'=>''], false],
            [['op'=>'div', 'x1'=>'a', 'y1'=>5, 'x2'=>6.5, 'y2'=>7], false],
            [['op'=>'sum', 'x1'=>1.3, 'y1'=>0, 'x2'=>'bdf567', 'y2'=>0], false]
        ];
    }
}
