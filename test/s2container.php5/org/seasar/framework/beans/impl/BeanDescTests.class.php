<?php
class BeanDescTests extends UnitTestCase {
   function __construct() {
       $this->UnitTestCase();
   }

   function testBeanDesc() {
       
       print __METHOD__ . "\n";       
    
       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);
       $this->assertIsA($desc,'S2Container_BeanDescImpl');

       $b = $desc->getBeanClass();
       $this->assertReference($a,$b);

       $constructor = $desc->getSuitableConstructor();
       $this->assertIsA($constructor,'ReflectionMethod');
       $this->assertEqual($constructor->getName(),'__construct');

       print "\n";
   }
   
   function testGetPropertyDesc() {

       print __METHOD__ . "\n";       

       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);

       $this->assertTrue($desc->hasPropertyDesc('val'));
       $this->assertTrue($desc->hasPropertyDesc('msg'));
       $this->assertTrue(!$desc->hasPropertyDesc('val3'));
       
       $propDesc = $desc->getPropertyDesc('val');
       $this->assertIsA($propDesc,'S2Container_PropertyDescImpl');
       $this->assertEqual($propDesc->getPropertyName(),'val');
       $this->assertTrue($propDesc->hasWriteMethod());
       $this->assertTrue($propDesc->hasReadMethod());
       $readMetnod = $propDesc->getReadMethod();
       $this->assertEqual($readMetnod->getName(),'getVal');
       $writeMetnod = $propDesc->getWriteMethod();
       $this->assertEqual($writeMetnod->getName(),'setVal');
       

       $propDesc = $desc->getPropertyDesc(0);
       $this->assertIsA($propDesc,'S2Container_PropertyDescImpl');
       $this->assertEqual($propDesc->getPropertyName(),'val');

       try{
           $propDesc = $desc->getPropertyDesc(2);
       }catch(Exception $e){
           $this->assertIsA($e,'S2Container_PropertyNotFoundRuntimeException');
       }

       try{
           $propDesc = $desc->getPropertyDesc('val2');
       }catch(Exception $e){
           $this->assertIsA($e,'S2Container_PropertyNotFoundRuntimeException');
       }
       
       print "\n";
   }

   function testGetField() {

       print __METHOD__ . "\n";       

       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);

       $this->assertTrue($desc->hasField('QUERY_1'));
       $this->assertTrue($desc->hasField('QUERY_2'));
       $this->assertTrue(!$desc->hasField('QUERY_3'));
       
       $field = $desc->getField('QUERY_1');
       $this->assertIsA($field,'ReflectionProperty');
       $this->assertEqual($field->getName(),'QUERY_1');
       
       try{
           $this->assertEqual($field->getValue(),'select * from talbe1;');
       }catch(Exception $e){
       	   $this->assertIsA($e,'ReflectionException');
       }

       try{
           $field = $desc->getField('QUERY_3');
       }catch(Exception $e){
       	   $this->assertIsA($e,'S2Container_FieldNotFoundRuntimeException');
       	   print $e->getMessage() . "\n";
       }

       print "\n";
   }

   function testGetConstant() {

       print __METHOD__ . "\n";       

       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);

       $this->assertTrue($desc->hasConstant('BEAN_A'));
       $this->assertTrue($desc->hasConstant('BEAN_B'));
       $this->assertTrue(!$desc->hasConstant('BEAN_C'));
       
       $value = $desc->getConstant('BEAN_A');
       $this->assertEqual($value,'TestBeanA');

       try{
           $field = $desc->getConstant('BEAN_C');
       }catch(Exception $e){
       	   $this->assertIsA($e,'S2Container_ConstantNotFoundRuntimeException');
       	   print $e->getMessage() . "\n";
       }

       print "\n";
   }

   function testGetMethods() {

       print __METHOD__ . "\n";       

       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);

       $om = $desc->getMethods('om1');
   	   $this->assertIsA($om,'ReflectionMethod');

       try{
           $om = $desc->getMethods('omX');
       }catch(Exception $e){
       	   $this->assertIsA($e,'S2Container_MethodNotFoundRuntimeException');
       	   print $e->getMessage() . "\n";
       }
       print "\n";
   }   
   
}

class BeanDescSample implements IO {

    const BEAN_A = "TestBeanA";
    const BEAN_B = "TestBeanB";

    private static $QUERY_1 = "select * from talbe1;";
    public static $QUERY_2 = "select * from table2;";
    
    private $msg;
    private $val;

    function __construct(){}
	function om1(){}
	function om2(){}

    function setVal($val){
    	$this->val = $val;
    }	
    function getVal(){
        return $this->val;	
    }
    
    function setMsg($msg){
    	$this->msg = $msg;
    }	
    function getMsg(){
        return $this->msg;	
    }
}
?>
