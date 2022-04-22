<?php

$db = mysqli_connect($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['name']);
if ($db == false) {
	exit("Koneksi database gagal: ".mysqli_connect_error());
}