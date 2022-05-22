<?php

/**
 * Usage example of class ComplexMath()
 * Use appache2 server (local), index.html, index.js
 * 
 * @author Git:@orsgen
 * @version 1.0.0
 * @copyright 20.05.2022
 */

require_once "complexmath.php";

//Input name of arytmetic operations (it's different from class name methods)
$Operations = array("sum", "diff", "mult", "div");

//List of input data errors
// index.js has validation before send data but sometimes may to get troubles without php-validation.
// It needs because any unhappy changes in js script or html, uncorrect usage of it can give error 
//    in this php script unvisible for user. 
$ErrorMessages = array(
    "Операция не указана",
    "Неизвестная операция над комплексными числами",
    "Левый операнд пуст",
    "Правый операнд пуст",
    "Левый операнд - не комплексное число",
    "Правый операнд - не комплексное число",
    "Деление на ноль"
);

//For collecting errors and returning full list of errors to Ajax for user 
$ErrorList = array();

/**
 * Validation of params got from Ajax
 * 
 * @param  array   $nums Content 5 named values: operator, left operand (a,b), right operand (a,b)
 * @return boolean true for valid data, false for invalid
 */
function  isvalid(array $nums) {
    global $Operations, $ErrorList;

    //Check name of operation
     if(empty($nums['op']))
         $ErrorList[] = 0;
     elseif(!in_array($nums['op'], $Operations)) 
         $ErrorList[] = 1;

     //Check left operand values 
     if((!array_key_exists('x1', $nums)||!strlen($nums['x1'])) && (!array_key_exists('y1', $nums)||!strlen($nums['y1'])))
         $ErrorList[] = 2;
     elseif(!is_numeric($nums['x1'])&&!empty($nums['x1']) || !is_numeric($nums['y1'])&&!empty($nums['y1']))
         $ErrorList[] = 4;

     //Check left operand values 
     if((!array_key_exists('x2', $nums)||!strlen($nums['x2'])) && (!array_key_exists('y2', $nums)||!strlen($nums['y2'])))
         $ErrorList[] = 3;
     elseif(!is_numeric($nums['x2'])&&!empty($nums['x2']) || !is_numeric($nums['y2'])&&!empty($nums['y2']))
         $ErrorList[] = 5;

     if(!empty($ErrorList))
         return false; 
         
     return true;
}

/**
 * Supposition: max value of decimal dimension used by calc user identical expected result dimension.
 * @param  array $numbers  Include operation named 'op', and operands values
 */
function calc_scale(array $numbers) {

    $dim = 0;
    foreach($numbers as $i=>$val) {
        if($i=='op')
            continue;
        else {
            if( ($pos = strpos($val, '.')) !== false)
                $dim = max($dim, strlen($val) - $pos - 1);
            else
                $dim = max($dim, 0);
        }
    }
    return $dim;
}

/*
* Emulation PHP Bcmath
*
* For PHP-hosters without php extension BcMath
*/
if(!extension_loaded("bcmath")) {
function    bcscale($a) {
}

function    bcadd($a, $b) {
 return $a+$b;
}

function    bcsub($a, $b) {
 return $a-$b;
}

function    bcmul($a,$b) {
 return round($a*$b, 5);
}

function    bcdiv($a,$b) {
 if($b==0) return null;
 return round($a/$b, 5);
}
}

/*
***** Usage of class ComplexMath() ************* 
*/
$params = $_POST;

if( ($valid = isvalid($params))) {
    //Calculate scale of result
    $scale = calc_scale($params);

    //Init vars for math operation
    $a = ComplexMath::create($params['x1'], $params['y1']);
    $b = ComplexMath::create($params['x2'], $params['y2']);
    
    //Cast operation name from ajax to ComplexMat() names
    switch($params['op']) {
        case 'sum': $math_op = 'add';
                    break;
        case 'diff': $math_op = 'sub';
                    break;
        case 'mult': $math_op = 'mul';
                    break;
        default:    $math_op = $params['op'];
    }    

    //Execute math operation
    $c = ComplexMath::$math_op($a, $b, $scale); 

    //Prepare result for form as ajax wait json
    if(($c instanceof ComplexMath) ) {
        $res = array(array('status'=>1, 'message'=>'Operation complete succesfuly!'), 
                    array('x'=>$c->a, 'y'=>$c->b));
    } else {
        $valid = false;
        $ErrorList[] = 6;
    }
}

if(!$valid) {
        //Fill validation errors from $Errorlist[]
        $res = array(array('status'=>0, 'message'=>'Error detected!'));
        foreach($ErrorList as $i=>$val) {
            $res[$i+1] = array();
            $res[$i+1]['errno'] = $val;
            $res[$i+1]['errmsg'] = $ErrorMessages[$val];
        }
}

//Return json to ajax query from index.js
echo json_encode($res, JSON_BIGINT_AS_STRING, 2);
