<?php
namespace sample;
class Hoge {
    /**
     * @param numeric $a is_numeric関数で確認されます。
     * @param string  $b is_string関数で確認されます。
     * @return object is_object関数で確認されます。
     */
    public function foo($a, $b) {
        return new StdClass;
    }

    /**
     * @param sample::Huga  $a Hugaクラスかどうか、または、is_subclass_of関数で確認されます。
     * @param mixed $b 型チェックを行いません。
     * @return null|sample::Huga is_null関数で確認されます。
     *                           または、Hugaクラスかどうか、または、is_subclass_of関数で確認されます。
     */
    public function bar(sample::Huga $a, $b) {
        return null;
    }
}
class Huga{}
