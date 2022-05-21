<?php
/**
 * !!! Required PHP BcMath
 * Complex numbers arithmetic operations
 * And it's usage from html+js+ajax page
 * 
 * @author Git:@orsgen
 * @version 1.0.0
 * @copyright 20.05.2022
 */

class Complex {
    
    const   DEFAULT_SCALE = 5;
    public  $Operations = array("sum", "diff", "mult", "div");
    public $ErrorMessages = array(
        "Операция не указана",
        "Неизвестная операция над комплексными числами",
        "Левый операнд пуст",
        "Правый операнд пуст",
        "Левый операнд - не комплексное число",
        "Правый операнд - не комплексное число",
        "Деление на ноль",
        "Операция с большими числами - требуется php-библиотека Bcmath"
    );
    public $ErrorList = array();

    function    __construct() {
    }

    public function  set_scale($scale=self::DEFAULT_SCALE) {
        bcscale($scale);
    }
    /**
     * Валидация входных параметров (на стороне php)
     */
    public function  isvalid(array $nums) {

        //Проверка оператора
        if(empty($nums['op']))
            $this->ErrorList[] = 0;
        elseif(!in_array($nums['op'], $this->Operations))
            $this->ErrorList[] = 1;

        if((!array_key_exists('x1', $nums)||!strlen($nums['x1'])) && (!array_key_exists('y1', $nums)||!strlen($nums['y1'])))
            $this->ErrorList[] = 2;
        elseif(!is_numeric($nums['x1'])&&!empty($nums['x1']) || !is_numeric($nums['y1'])&&!empty($nums['y1']))
            $this->ErrorList[] = 4;

        if((!array_key_exists('x2', $nums)||!strlen($nums['x2'])) && (!array_key_exists('y2', $nums)||!strlen($nums['y2'])))
            $this->ErrorList[] = 3;
        elseif(!is_numeric($nums['x2'])&&!empty($nums['x2']) || !is_numeric($nums['y2'])&&!empty($nums['y2']))
            $this->ErrorList[] = 5;

        if(!empty($this->ErrorList))
            return false; 
            
        return true;
    }

    /**
     * Расчетная формула точности - максимальная разрядность чисел + 2.
     * Предположение. Пользователь ожидает точность сравнимую, с указанной при задании
     * параметров
     */
    public function calc_scale($numbers) {
 
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
        return $dim + 2;
    }


    public function  sum(array $numbers) {
        return array('x'=>bcadd($numbers['x1'],$numbers['x2']), 
                'y'=>bcadd($numbers['y1'],$numbers['y2']));
    }
    
    public function  diff(array $numbers) {
        return array('x'=>bcsub($numbers['x1'],$numbers['x2']), 
                'y'=>bcsub($numbers['y1'],$numbers['y2']));
    }
    
    public function  mult(array $numbers) {
        return array('x'=>bcsub(bcmul($numbers['x1'],$numbers['x2']), 
                bcmul($numbers['y1'],$numbers['y2'])), 
            'y'=>bcadd(bcmul($numbers['x1'],$numbers['y2']), 
                bcmul($numbers['y1'],$numbers['x2'])));        
    }
    
    public function  div(array $numbers) {
        $up1 = bcadd(bcmul($numbers['x1'],$numbers['x2']), bcmul($numbers['y1'],$numbers['y2']));
        $up2 = bcsub(bcmul($numbers['y1'],$numbers['x2']), bcmul($numbers['x1'],$numbers['y2']));
        $down = bcadd(bcmul($numbers['x2'],$numbers['x2']),bcmul($numbers['y2'],$numbers['y2']));
      
        return array('x'=>bcdiv($up1, $down), 'y'=>bcdiv($up2, $down));                
    }
}

/*
Emulation PHP Bcmath
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
Usage of class Complex()
*/
$params = $_POST; 
//$params = $_GET; //Decommented for debug php url from browser string
$obj = new Complex();

if($obj->isvalid($params)) {
    //Рассчитываем точность вычислений
    $obj->set_scale($obj->calc_scale($params));

    //Выполняем операцию
    $operation = $params['op'];
    $calc_res = $obj->$operation($params); //array('x'=>,'y'=>)

    //Подготавливаем возвращаемый результат
    if(($calc_res['x'] !==null) && ($calc_res['y']!==null))
        $res = array(array('status'=>1, 'message'=>'Operation complete succesfuly!'), $calc_res);
    else 
        $res = array(array(0, 'Error detected!'),
                    array(6, $obj->ErrorMessages[6]));
} else {
    //Формируем массив со списком ошибок
    $res = array(array('status'=>0, 'message'=>'Error detected!'));
    foreach($obj->ErrorList as $i=>$val) {
        $res[$i+1] = array();
        $res[$i+1]['errno'] = $val;
        $res[$i+1]['errmsg'] = $obj->ErrorMessages[$val];
    }
}

echo json_encode($res,JSON_BIGINT_AS_STRING,2);
