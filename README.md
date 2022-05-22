
Job for test task "To develop php class for math operations about complex numbers and unit-tests"


I made 3 versions for this task.

1. I made html, js and php for complex math operations. But I think - my job isn't the same task.
My class and interface work but don't contain main object - complex number!)
After it I develop more classic version 2.

2. I use the same html, js and develop new class with 2 properties - real & imaging part. So PHP is non-strict language about data types and
arythmetic operations I didn't realize throw exception from constructor of class for invalid values of numbers. It works too.
But... I feel some bad so my complex numbers and operations weren't full identical to PHP ordinary: 1+null = null and my "add" gave Fatal PHP error!)
And I develop another version with some small trick - version 3.

3. I think - can I use private constructor of class?) Yes, can! And I add method create() for validate input numbers for creating complex number.
For invalid numbers I return null, for good - create my object. Bingo!

    complexmath.php - class ComplexMath(). Methods (static): add($a, $b), sub($a, $b), mul($a, $b), div($a, $b).
    Reqired extension: bcmath
    PHP 8.0 
    
    Additionlly: 

        -use_cm.php Example for usage this class

        -index.html Starting page for testing this class

        -index.js   js for post ajax query to server and get json result from it

        Tests/complexmathtest.php - set of full-covered tests for class ComplexMath()

Note: Project haven't css and use very simple&pure html!

Released: 20/05/2022 - 22/05/2022

Tested: PHP 8.0, Firefox version 100.0 (64-bit), Google Chrome 101.0.4951.64 (64-bit), PHPUnit 9.5.20