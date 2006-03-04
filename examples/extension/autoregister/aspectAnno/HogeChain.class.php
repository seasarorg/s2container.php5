<?
interface IHogeChainM1 {
    public function m1($a,$b);
}

interface IHogeChainM2 {
    public function m2($a,$b);
}

interface IHogeChainM3 extends IHogeChainM2{
    public function m3($a,$b);
}

class HogeChain implements IHogeChainM1,IHogeChainM3{
    //const ASPECT = "interceptor = chain";
    const ASPECT = "chain";

    public function m1($a,$b){
        return $a + $b;
    }

    public function m2($a,$b){
        return $a * $b;
    }

    public function m3($a,$b){
        return $a - $b;
    }

    public function m4($a,$b){
        return $a / $b;
    }
}

?>
