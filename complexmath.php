<?php
/**
 * !!! Required PHP BcMath
 * Complex numbers arithmetic operations
 * 
 * @author Git:@orsgen
 * @version 1.0.0
 * @copyright 21.05.2022
 */
class ComplexMath {
    
    const   DEF_SCALE = 2;      //Default result dimension
    const   ADD_SCALE = 2;      //Addition scale for improving precision of calculation

    public float   $a;         //Real part of complex number
    public float   $b;         //Imaginary part of complex number
    private int    $scale;     //Dimensions (number digits after decimal point it needs in result value)
    
    function    __construct($a, $b, $scale=self::DEF_SCALE) {
        /*
            Some tasks can include validation in math class, but base concept of php - no validation with math operations!
            In this case I'll write it by throw exceptions in this constructor:
            if(bad value) throw(..);
        */
        $this->a = $a;
        $this->b = $b;
        $this->scale = $scale;
    }

    /**
     * @param $scale - int positive value (>=0)
     * @return old value of scale (The same as bcscale()!)
     * 
     */
    public function  set_scale($scale=self::DEFAULT_SCALE) {
        $this->scale = $scale;
        return   bcscale($scale);      //Set scale for bcmath and return old value
    }

    public function  add(ComplexMath $x, ComplexMath $y, $scale=self::DEF_SCALE) {
        return new ComplexMath(bcadd($x->a, $y->a, $scale+self::ADD_SCALE),
                    bcadd($x->b, $y->b, $scale+self::ADD_SCALE));
    }
    
    public function  sub(ComplexMath $x, ComplexMath $y, $scale=self::DEF_SCALE) {
        return new ComplexMath(bcsub($x->a, $y->a, $scale+self::ADD_SCALE),
                    bcsub($x->b, $y->b, $scale+self::ADD_SCALE));
    }
    
    public function  mul(ComplexMath $x, ComplexMath $y, $scale=self::DEF_SCALE) {
        return new ComplexMath(bcsub(bcmul($x->a,$y->a,$scale+self::ADD_SCALE), bcmul($x->b,$y->b,$scale+self::ADD_SCALE)), 
                                bcadd(bcmul($x->a,$y->b,$scale+self::ADD_SCALE), bcmul($x->b,$y->a,$scale+self::ADD_SCALE)));        
    }
    
    public function  div(ComplexMath $x, ComplexMath $y, $scale=self::DEF_SCALE) {
        //fraction numerator (real part)
        $up1 = bcadd(bcmul($x->a,$y->a,$scale+self::ADD_SCALE), bcmul($x->b,$y->b,$scale+self::ADD_SCALE));
        //fraction nominator (imaginary)
        $up2 = bcsub(bcmul($x->b,$y->a,$scale+self::ADD_SCALE), bcmul($x->a,$y->b,$scale+self::ADD_SCALE));
        //denominator for both parts
        $down = bcadd(bcmul($y->a,$y->a,$scale+self::ADD_SCALE),bcmul($y->b,$y->b,$scale+self::ADD_SCALE));
      
        return new ComplexMath(bcdiv($up1,$down,$scale+self::ADD_SCALE), bcdiv($up2,$down,$scale+self::ADD_SCALE));                
    }
}

