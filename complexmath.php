<?php
/**
 * @require PHP >=8.0, PHP BcMath
 * Complex numbers arithmetic operations
 * 
 * PHP concept about arithmetic operations (you can add null to number) and about construct objects concept
 * (constructor can't return null - only throw exception) has contradiction. We can get the same behavior for object "complex number"
 * by using some tricks:
 *      - private constructor of object;
 *      - public static method to create new object with validation of input data. This method return for non-valid values null, 
 *          for NaN input - NaN and use constructor for valid values.
 *      - static methods for math operations with valid control input data.
 * 
 * @author Git:@orsgen
 * @version 2.0.0
 * @copyright 22.05.2022
 */
class ComplexMath {
    
    const   DEF_SCALE = 0;      //Default result dimension
    const   ADD_SCALE = 2;      //Addition scale for improving precision of calculation

    //Used type string for correct result for big numbers more than bit depth of standard PHP number's type
    //See declaration of properties - in constructor()!
    
    /**
     * Class construct & declare $a, $b (starting from PHP 8.0)
     * It can be called ONLY from this class 
     * For creating object use public static method self::create()!
     * 
     * @param   string  $a      Real part of complex number. Use type string for use very big numbers
     * @param   string  $b      Imaginary part of complex number
     */
    private function    __construct(public string $a, public string $b) {
        $this->a = $a;
        $this->b = $b;
    }

    /**
     * Check valid $a, $b. Its must be a numeric (int, float, very long string with only digits and one decimal point)
     * Check $scale for positive int
     * Create new instant of object "complex number" or return null for bad input data
     * 
     * @param   $a      real part of complex number
     * @param   $b      imaginary part of number
     * 
     * @return new object or null for bad input data  
     */
    public static function create($a, $b) {
        if(is_numeric($a) && is_numeric($b)) {
            return new self($a, $b);
        } else 
            return null;
    }

    /**
     * Check valid of params before used operation about complex number
     * Because PHP 8.0 declare NaN!==Nan it needs return NaN as we got it.
     * 
     * @param   ComplexNumber   $x
     * @param   ComplexNumber   $y
     * @param   int             $scale
     * 
     * @return true for valid params, value of invalid complex number, null for bad scale 
     */
    private static function isvalid(self $x, self $y, int $scale) {
        
        //$x - isn't complex number
        if(!($x instanceof self))
            return $x;
        //$y - isn't complex number
        if(!($y instanceof self))
            return $y;
        //Scale isn't valid
        if(!($scale>=0))
            return null;

        //Data is valid    
        return true;
    }
    
    /**
     * Add complex number $a to complex number $b, return new complex number for valid data.
     * For invalid data return first invalid param
     * 
     * @param   ComplexNumber   $x
     * @param   ComplexNumber   $y
     * @param   int             $scale
     *  
     */
    public static function  add(self $x, self $y, int $scale=self::DEF_SCALE) {

        if(($val = self::isvalid($x, $y, $scale)) === true) {
            //Valid complex numbers & scale 
            return new self(bcadd($x->a, $y->a, $scale),
                    bcadd($x->b, $y->b, $scale));
        } else
            return $val;
    }
    
    /**
     * Subtract complex number $b from complex number $a, return new complex number for valid data.
     * For invalid data return first invalid param
     * 
     * @param   ComplexNumber   $x
     * @param   ComplexNumber   $y
     * @param   int             $scale
     *  
     */
    public static function  sub(ComplexMath $x, ComplexMath $y, $scale=self::DEF_SCALE) {
        if(($val = self::isvalid($x, $y, $scale)) === true)
            //Valid complex numbers & scale 
            return new self(bcsub($x->a, $y->a, $scale),
                    bcsub($x->b, $y->b, $scale));
        else
            return $val;
    }
    
    /**
     * Multiple complex number $a to complex number $b, return new complex number for valid data.
     * For invalid data return first invalid param
     * 
     * @param   ComplexNumber   $x
     * @param   ComplexNumber   $y
     * @param   int             $scale
     *  
     */
    public static function  mul(ComplexMath $x, ComplexMath $y, $scale=self::DEF_SCALE) {
        if(($val = self::isvalid($x, $y, $scale)) === true)
            //Valid complex numbers & scale 
            return new self(bcsub(bcmul($x->a,$y->a,$scale+self::ADD_SCALE), bcmul($x->b,$y->b,$scale+self::ADD_SCALE), $scale), 
                                bcadd(bcmul($x->a,$y->b,$scale+self::ADD_SCALE), bcmul($x->b,$y->a,$scale+self::ADD_SCALE), $scale));        
        else
            return $val;
    }
    
    /**
     * Divide complex number $a to complex number $b, return new complex number for valid data.
     * For invalid data return first invalid param
     * 
     * @param   ComplexNumber   $x
     * @param   ComplexNumber   $y
     * @param   int             $scale
     *  
     */
    public static function  div(ComplexMath $x, ComplexMath $y, $scale=self::DEF_SCALE) {
        if(($val = self::isvalid($x, $y, $scale)) === true) {
            //Valid complex numbers & scale 
            //Calc fraction numerator (real part) for long formula
            $up1 = bcadd(bcmul($x->a,$y->a,$scale+self::ADD_SCALE), bcmul($x->b,$y->b,$scale+self::ADD_SCALE));
            //Calc fraction nominator (imaginary)
            $up2 = bcsub(bcmul($x->b,$y->a,$scale+self::ADD_SCALE), bcmul($x->a,$y->b,$scale+self::ADD_SCALE));
            //Calc denominator for both parts
            $down = bcadd(bcmul($y->a,$y->a,$scale+self::ADD_SCALE),bcmul($y->b,$y->b,$scale+self::ADD_SCALE));
      
            return new self(bcdiv($up1,$down,$scale), bcdiv($up2,$down,$scale));                
        } else
            return $val;
    }
}

