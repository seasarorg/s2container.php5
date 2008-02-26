<?php
/**
 * @S2Component('available' => false)
 */
class Paginate {
    /**
     * @var integer
     */
    private $offset = 0;

    /**
     * @var integer
     */
    private $limit  = 10;

    /**
     * @var integer
     */
    private $page   = 1;

    /**
     * @var integer
     */
    private $window = 5;

    /**
     * @var integer|null
     */
    protected $total  = null;

    /**
     * 全ページ数を返します。
     * @return integer
     */
    final public function getTotalPage() {
        return ceil($this->getTotal() / $this->limit);
    }

    /**
     * @return integer
     */
    final public function getPage() {
        return $this->page;
    }

    /**
     * ページ番号が0～全ページ数のページ番号を設定します。
     *
     * @param integer $page
     * @throw Exception
     */
    final public function setPage($page) {
        if ($page < 0 or $this->getTotalPage() < $page) {
            throw new Exception("$page is out of range 0-{$this->getTotalPage()}");
        }
        if ($this->page < $page) {
            $this->offset = $this->offset + $this->limit * ($page - $this->page);
        } else if ($this->page > $page) {
            $this->offset = $this->offset - $this->limit * ($this->page - $page);
        }
        $this->page = $page;
    }

    /**
     * offset値を初期化します。
     */
    final public function initOffset() {
        $this->offset = 0;
    }

    /**
     * @return integer
     */
    final public function getOffset() {
        return $this->offset;
    }

    /**
     * @return integer
     */
    final public function getLimit() {
        return $this->limit;
    }

    /**
     * @prama integer $limit
     */
    final public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * @return integer
     * @throw Exception 全件数が未設定の場合にスローされます。
     */
    final public function getTotal() {
        if ($this->total === null) {
            throw new Exception('total not set.');
        }
        return $this->total;
    }

    /**
     * @param integer $total
     */
    final public function setTotal($total) {
        $this->total = $total;
    }

    /**
     * @return integer
     */
    final public function getWindow($window) {
        return $this->window;
    }

    /**
     * @param integer $window
     */
    final public function setWindow($window) {
        $this->window = $window;
    }

    /**
     * ページ番号を進めます。あわせてoffset値を増加します。
     */
    final public function next() {
        if ($this->getTotalPage() == $this->page) {
            return;
        }
        $this->offset = $this->offset + $this->limit;
        $this->page++;
    }

    /**
     * @return boolean
     */
    final public function isNext() {
        return $this->page < $this->getTotalPage();
    }

    /**
     * ページ番号を戻します。あわせてoffset値を減らします。
     */
    final public function prev() {
        if ($this->page == 1) {
            return;
        }
        $this->page--;
        $this->offset = $this->offset - $this->limit;
    }

    /**
     * @return boolean
     */
    final public function isPrev() {
        return 1 < $this->page;
    }

    /**
     * window内に収まるページ番号を列挙します。
     *
     * @return array
     */
    final public function pages() {
        $diff = round($this->window / 2);
        $pages = array();
        $totalPage = $this->getTotalPage();
        $i = 0;
        $j = $this->page - $diff + 1;
        while($i < $this->window) {
            if (0 < $j and $j <= $totalPage) {
                $pages[] = $j;
            }
            $j++;
            $i++;
        }
        return $pages;
    }

    /**
     * Daoを用いてpaginateされた結果を得ます。
     * 
     * @param object $dao
     * @param string $methodName
     * @return array
     * @throw Exception
     *        Daoが$methodNameメソッドを実装していない場合にスローされます。
     *        Daoが$methodName . 'Total' メソッドを実装していない場合にスローされます。
     *        全件数が取得できない場合にスローされます。
     */
    final public function find($dao, $methodName) {
        $totalMethodName = $methodName . 'Total';

        if (!in_array($methodName, get_class_methods($dao))) {
            throw new Exception("method [$methodName] not found.");
        }

        if (!in_array($totalMethodName, get_class_methods($dao))) {
            throw new Exception("method [$totalMethodName] not found.");
        }

        $rows = $dao->$totalMethodName($this);
        if (count($rows) != 1) {
            throw new Exception('could not get total.');
        }

        $values = array_values((array)$rows[0]);
        if (count($values) != 1) {
            throw new Exception('could not specify total.');
        }

        if (! is_numeric($values[0])) {
            throw new Exception('invalid total:' . $values[0]);
        }

        $this->setTotal($values[0]);
        return $dao->$methodName($this);
    }
}
