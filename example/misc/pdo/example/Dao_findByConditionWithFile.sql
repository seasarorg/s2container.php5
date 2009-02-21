select * from emp where
<?php if(is_int($id)):?>
  EMPNO = :id
<?php endif;?>
