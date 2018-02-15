<?php
//$conf = parse_ini_file("config.ini");
//$link = mysql_connect($conf['host'], $conf['username'], $conf['password'])
$link = mysql_connect('localhost', 'root', 'd3v3l0p3rs')
        or die('No se pudo conectar: ' . mysql_error());
mysql_select_db('quot_symfony') or die('No se pudo seleccionar la base de datos');
