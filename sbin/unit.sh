#!/bin/sh

php build.php
cd ../test

if [ "x$1" = "x" ];then
  phpunit --bootstrap test.inc.php seasar
else
  phpunit --bootstrap test.inc.php --filter $1 seasar
fi
