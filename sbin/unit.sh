#!/bin/sh

php build.php
cd ../test
phpunit --bootstrap test.inc.php --filter $1 seasar

