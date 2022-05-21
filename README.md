
For test task "To develop php class for math operations about complex numbers and unit-tests":
(1) Classic OOP 
    complexmath.php Class ComplexMath(). Methods: add($a, $b), sub($a, $b), mul($a, $b), div($a, $b), set_scale($a).
    Reqired extension: bcmath 
    Additionlly develop: 
        -use_cm.php Example for usage this class
        -index.html Starting page for testing this class
        -index.js   js for post ajax query to server and get json result from it
        Tests/complexmathtest.php - set of full-covered tests for class ComplexMath()
Note: 1. As PHP arythmetic operations don't throw exceptions, my class don't validate data too.
    Example of validation for usage can get from use_cm.php
    2. Project haven't css and use very simple html!

(2) Minimal OOP
    -complex.php class Complex() and example of usage. Class don't create object "complex number" as to demand OOP and only realize some obviously methods for implementation task develop web-"Complex number calculator". In some cases this decision better than classic OOP.
    -index.html The same with (1)
    -index.js   For use it needs to change url for ajax query from "/use_cm.php" to "/complex.php".
Note: So (2) develop by php 5.6, may be php code include some small bugs

Released: index.html            20/05/2022
          index.js              20/05/2022
          complex.php           20/05/2022
          complexmath.php       21/05/2022
          Test/complexmath.php  21/05/2022

Tested: PHP 8.0, Firefox version 100.0 (64-bit), Google Chrome 101.0.4951.64 (64-bit), PHPUnit 9.5.20