<?php
use seasar\container\S2ApplicationContext as s2app;

require_once(S2A5_ROOT . '/Generator/Postgres.php');
require_once(S2A5_ROOT . '/Writer/File.php');

s2app::register('Seasar_A5_Parser');
s2app::register('Seasar_A5_Generator_Postgres');
s2app::register('Seasar_A5_Writer_File');

