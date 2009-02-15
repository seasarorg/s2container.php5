<?php
/**
 * @S2Component('available' => false);
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
     * @var array
     */
    protected $data = null;

    /**
     * @param array $data
     * @return integer
     */
    final public function setData(array $data) {
        $this->total = count($data);
        $this->data = $data;
        return $this->total;
    }

    /**
     * @return array
     */
    final public function getData() {
        if ($this->offset + $this->limit < $this->total) {
            $len = $this->limit;
        } else {
            $len = $this->total - $this->offset;
        }
        return array_slice($this->data, $this->offset, $len);
    }

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
        if ($page < 1 or $this->getTotalPage() < $page) {
            throw new Exception("$page is out of range 1-{$this->getTotalPage()}");
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
     * @return boolean
     */
    final public function hasTotal() {
        return !($this->total === null);
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
        if ($window < 2) {
            $window = 2;
        }
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
        if ($this->getTotalPage() <= $this->window) {
            $pages = range(1, $this->getTotalPage());
        } else {
            $start = $this->page - floor($this->window / 2);
            $start = $start < 1 ? 1 : $start;
            $pages = range($start, $start + $this->window -1);
            if ($this->getTotalPage() < $pages[$this->window - 1]) {
                $diff = $pages[$this->window - 1] - $this->getTotalPage();
                foreach ($pages as &$page) {
                    $page -= $diff;
                }
            }
        }
        return $pages;
    }
}
