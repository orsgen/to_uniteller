<?php
require 'complexmath.php';

use PHPUnit\Framework\TestCase;

class ComplexMathTest extends TestCase {

    protected   $complex;
    
    /**
     * @dataProvider isvalidProvider
     */
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
        $this->complex = NULL;
    }

    /**
     * Test for add()
     * @dataProvider addProvider
     */
    public function testadd(array $nums, array $exp) {
        
        $a = $this->complex = ComplexMath::create($nums[0],$nums[1]);
        $b = $this->complex = ComplexMath::create($nums[2],$nums[3]);
        $c = $this->complex->add($a, $b, $nums[4]);
        $this->assertSame($exp[0], $c->a);
        $this->assertSame($exp[1], $c->b);
    }

    public function addProvider()
    {
        return [
            [[0, 0, 0, 0, 1], ['0.0','0.0']],
            [[1, 2, 2, 3, 0], ['3','5']],
            [[1, 0, -1, -1, 2], ['0.00','-1.00']],
            [[250, 13, 0, -25, 3], ['250.000','-12.000']]
        ];
    }

    /**
     * Test for sub()
     * @dataProvider subProvider
     */
    public function testsub(array $nums, array $exp) {
        
        $a = $this->complex = ComplexMath::create($nums[0],$nums[1]);
        $b = $this->complex = ComplexMath::create($nums[2],$nums[3]);
        $c = $this->complex->sub($a,$b,$nums[4]);
        $this->assertSame($exp[0], $c->a);
        $this->assertSame($exp[1], $c->b);
    }

    public function subProvider()
    {
        return [
            [[0, 0, 0, 0, 1], ['0.0','0.0']],
            [[1, 2, 2, 3, 0], ['-1','-1']],
            [[1, 0, -1, -1, 2], ['2.00','1.00']],
            [[250, 13, 0, -25, 3], ['250.000','38.000']]
        ];
    }

    /**
     * Test for mul()
     * @dataProvider mulProvider
     */
    public function testmul(array $nums, array $exp) {
        
        $a = $this->complex = ComplexMath::create($nums[0],$nums[1]);
        $b = $this->complex = ComplexMath::create($nums[2],$nums[3]);
        $c = $this->complex->mul($a,$b,$nums[4]);
        $this->assertSame($exp[0], $c->a);
        $this->assertSame($exp[1], $c->b);
    }

    public function mulProvider()
    {
        return [
            [[0, 0, 0, 0, 1], ['0.0','0.0']],
            [[1, 2, 2, 3, 0], ['-4','7']],
            [[1, 0, -1, -1, 2], ['-1.00','-1.00']],
            [[250, 13, 0, -25, 3], ['325.000','-6250.000']]
        ];
    }

    /**
     * Test for div()
     * @dataProvider divProvider
     */
    public function testdiv(array $nums, array $exp) {
        
        $a = $this->complex = ComplexMath::create($nums[0],$nums[1]);
        $b = $this->complex = ComplexMath::create($nums[2],$nums[3]);
        $c = $this->complex->div($a,$b,$nums[4]);
        $this->assertSame($exp[0], $c->a);
        $this->assertSame($exp[1], $c->b);
    }

    public function divProvider()
    {
        return [
            [[1, 2, 2, 3, 4], ['0.6153','0.0769']],
            [[1, 0, -1, -1, 1], ['-0.5','0.5']],
            [[250, 13, 0, -25, 2], ['-0.52','10.00']]
        ];
    }

    /**
     * In this cases must be PHP Error
     */
    public function testFailing__construct()
    {
        $a = ComplexMath::create('',1);
        $this->assertSame(null, $a);
        $a = ComplexMath::create(1,'');
        $this->assertSame(null, $a);
        $a = ComplexMath::create(null,1);
        $this->assertSame(null, $a);
        $a = ComplexMath::create(1,null);
        $this->assertSame(null, $a);
    }

    /**
     * In this cases must be PHP Error
     */
    public function testFailingops__construct()
    {
        $this->expectExceptionMessage('Division by zero'::class);
        $a = ComplexMath::create(1,1);
        $b = ComplexMath::create(0,0);
        $c = ComplexMath::div($a,$b);
    }
}
