<?
class HogeImpl {
    private $value;
    const value_BINDING = '" string value must be quoted "';

    /**
     * @S2Container_BindingAnnotation('" string value must be quoted "')
     */
    public function setValue($value){
        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }
}
?>
