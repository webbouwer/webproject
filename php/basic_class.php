<?php 
// https://code.tutsplus.com/tutorials/object-oriented-php-for-beginners--net-12762
// http://php.net/manual/en/language.oop5.php:
class nameOfTheClass { 
    
    public $prop1 = "I'm a class property!";
    
    function nameOfTheClass() { 
        print "This function is called on class creation"; 
    }
    function aClassfunc() { 
        print 'second class'; 
    } 
} 

$myclass = new nameOfTheClass;  // prints "This function is called on class creation"; 

print $myclass->$prop1; // prints "I'm a class property!"

print $myclass->aClassfunc; // prints "second class" 


// run function inside class
function getVarName() 
{ 
return 'prop1'; 
} 

print $myclass->{getVarName()}; // prints "I'm a class property!" 
?> 
