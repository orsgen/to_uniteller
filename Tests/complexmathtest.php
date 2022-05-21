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
        
        $a = $this->complex = new ComplexMath($nums[0],$nums[1]);
        $b = $this->complex = new ComplexMath($nums[2],$nums[3]);
        $c = $this->complex->add($a,$b);
        $this->assertSame($exp[0], $c->a);
        $this->assertSame($exp[1], $c->b);
    }

    public function addProvider()
    {
        return [
            [[0, 0, 0, 0], [0.0,0.0]],
            [[1, 2, 2, 3], [3.0,5.0]],
            [[1, 0, -1, -1], [0.0,-1.0]],
            [[250, 13, 0, -25], [250.0,-12.]]
        ];
    }

    /**
     * Test for sub()
     * @dataProvider subProvider
     */
    public function testsub(array $nums, array $exp) {
        
        $a = $this->complex = new ComplexMath($nums[0],$nums[1]);
        $b = $this->complex = new ComplexMath($nums[2],$nums[3]);
        $c = $this->complex->sub($a,$b);
        $this->assertSame($exp[0], $c->a);
        $this->assertSame($exp[1], $c->b);
    }

    public function subProvider()
    {
        return [
            [[0, 0, 0, 0], [0.0,0.0]],
            [[1, 2, 2, 3], [-1.,-1.0]],
            [[1, 0, -1, -1], [2.0,1.0]],
            [[250, 13, 0, -25], [250.0,38.]]
        ];
    }

    /**
     * Test for mul()
     * @dataProvider mulProvider
     */
    public function testmul(array $nums, array $exp) {
        
        $a = $this->complex = new ComplexMath($nums[0],$nums[1]);
        $b = $this->complex = new ComplexMath($nums[2],$nums[3]);
        $c = $this->complex->mul($a,$b);
        $this->assertSame($exp[0], $c->a);
        $this->assertSame($exp[1], $c->b);
    }

    public function mulProvider()
    {
        return [
            [[0, 0, 0, 0], [0.0,0.0]],
            [[1, 2, 2, 3], [-4.,7.0]],
            [[1, 0, -1, -1], [-1.0,-1.0]],
            [[250, 13, 0, -25], [325.0,-6250.]]
        ];
    }

    /**
     * Test for div()
     * @dataProvider divProvider
     */
    public function testdiv(array $nums, array $exp) {
        
        $a = $this->complex = new ComplexMath($nums[0],$nums[1]);
        $b = $this->complex = new ComplexMath($nums[2],$nums[3]);
        $c = $this->complex->div($a,$b);
        $this->assertSame($exp[0], $c->a);
        $this->assertSame($exp[1], $c->b);
    }

    public function divProvider()
    {
        return [
            [[1, 2, 2, 3], [0.6153,0.0769]],
            [[1, 0, -1, -1], [-0.5,0.5]],
            [[250, 13, 0, -25], [-0.52,10.0]]
        ];
    }

    /**
     * Test for set_scale()
     * NB! Method must return old value!
     * @dataProvider set_scaleProvider
     */
    public function testset_scale(int $scale, int $exp) {
        
        $a = new ComplexMath(0,0,0);
        $old_scale = $a->set_scale($scale);
        $this->assertSame($exp, $old_scale);
    }

    public function set_scaleProvider()
    {
        return [
            [2, 0],
            [10, 2],
            [0, 10]
        ];
    }

    /**
     * In this cases must be PHP Error
     */
    public function testFailing__construct()
    {
        $this->expectExceptionMessage('Cannot assign string to property ComplexMath'::class);
        $a = new ComplexMath('',1,0);
        $a = new ComplexMath(1,'',0);
        $a = new ComplexMath(null,1,0);
        $a = new ComplexMath(1,null, 0);
    }

    /**
     * In this cases must be PHP Error
     */
    public function testFailingops__construct()
    {
        $this->expectExceptionMessage('Division by zero'::class);
        $a = new ComplexMath(1,1);
        $b = new ComplexMath(0,0);
        $c = $b->div($a,$b);
    }
}
