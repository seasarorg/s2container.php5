<?php
/**
 * @S2Component('available' => false)
 */
class SqlFileReader {
    /**
     * SQLファイルからSQLクエリを読み込みます。
     *
     * @param string $sqlFile__
     * @param array $context__
     * @return string
     * @throw Exception ob_start、ob_get_contents、ob_end_cleanに失敗した場合にスローされます。
     */
    public static function read($sqlFile__, array $context__ = array()) {
        $status = ob_start();
        if ($status === false) {
            throw new Exception('ob_start fail.');
        }
        foreach ($context__ as $name => $value) {
            $$name = $value;
        }
        require($sqlFile__);
        $sql = ob_get_contents();
        if ($sql === false) {
            throw new Exception('ob_get_contents fail.');
        }
        $status = ob_end_clean();
        if ($status === false) {
            throw new Exception('ob_end_clean fail.');
        }
        return $sql;
    }
}
