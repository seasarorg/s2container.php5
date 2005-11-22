<?php
require_once(dirname(__FILE__) . "/../s2container.inc.php");
define("TEST_DIR", dirname(__FILE__));
class S2Container_TestCaseClasssLoader{
        private static $testData =
    array("A" => "sample/A.class.php",
          "AW" => "sample/AW.class.php",
          "B" => "sample/B.class.php",
          "C" => "sample/C.class.php",
          "D" => "sample/D.class.php",
          "Date" => "sample/Date.class.php",
          "DelegateA" => "sample/DelegateA.class.php",
          "DelegateB" => "sample/DelegateB.class.php",
          "E" => "sample/E.class.php",
          "F" => "sample/F.class.php",
          "G" => "sample/G.class.php",
          "H" => "sample/H.class.php",
          "I" => "sample/I.class.php",
          "J" => "sample/J.class.php",
          "K" => "sample/K.class.php",
          "L" => "sample/L.class.php",
          "M" => "sample/M.class.php",
          "M2" => "sample/M2.class.php",
          "N" => "sample/N.class.php",
          "O" => "sample/O.class.php",
          "P" => "sample/P.class.php",
          "Q" => "sample/Q.class.php",
          "R" => "sample/R.class.php",
          "S" => "sample/S.class.php",
          "T" => "sample/T.class.php",
          "U" => "sample/U.class.php",
          "V" => "sample/V.class.php",
          "W" => "sample/W.class.php",
          "WextendAW" => "sample/WextendAW.class.php",
          "X" => "sample/X.class.php",
          "Y" => "sample/Y.class.php",
          "Z" => "sample/Z.class.php",
          "IAddAction" => "sample/action/IAddAction.class.php",
          "IDivAction" => "sample/action/IDivAction.class.php",
          "ISubAction" => "sample/action/ISubAction.class.php",
          "AddActionImpl" => "sample/action/impl/AddActionImpl.class.php",
          "DivActionImpl" => "sample/action/impl/DivActionImpl.class.php",
          "SubActionImpl" => "sample/action/impl/SubActionImpl.class.php",
          "IDeptDao" => "sample/db/IDeptDao.class.php",
          "IEmpDao" => "sample/db/IEmpDao.class.php",
          "IEmpLogic" => "sample/db/IEmpLogic.class.php",
          "ADOdbDeptDaoImpl" => "sample/db/impl/ADOdbDeptDaoImpl.class.php",
          "EmpDto" => "sample/db/impl/EmpDto.class.php",
          "EmpLogicImpl" => "sample/db/impl/EmpLogicImpl.class.php",
          "MySQLDeptDaoImpl" => "sample/db/impl/MySQLDeptDaoImpl.class.php",
          "PearDBDeptDaoImpl" => "sample/db/impl/PearDBDeptDaoImpl.class.php",
          "PostgresDeptDaoImpl" => "sample/db/impl/PostgresDeptDaoImpl.class.php",
          "IA" => "sample/interface/IA.class.php",
"IB" => "sample/interface/IB.class.php",
          "IDelegateA" => "sample/interface/IDelegateA.class.php",
          "IDelegateB" => "sample/interface/IDelegateB.class.php",
          "IG" => "sample/interface/IG.class.php",
          "IJ" => "sample/interface/IJ.class.php",
          "IK" => "sample/interface/IK.class.php",
          "IO" => "sample/interface/IO.class.php",
          "IP" => "sample/interface/IP.class.php",
          "IW" => "sample/interface/IW.class.php",
          "IZ" => "sample/interface/IZ.class.php",
          "ICalculator" => "sample/logic/ICalculator.class.php",
          "CalculatorImpl" => "sample/logic/impl/CalculatorImpl.class.php");
    public static function load($name){
        if(isset(self::$testData[$name])){
            require_once(self::$testData[$name]);
        }
    }
}

function __autoload($class=null){
    if(S2ContainerClassLoader::load($class)){return;}
    if(S2Container_TestCaseClasssLoader::load($class)){return;}
    if($class == "S2Container_AbstractInterceptor"){
        require_once("org/seasar/framework/aop/interceptors/S2Container_AbstractInterceptor.class.php");
    }
}    
?>