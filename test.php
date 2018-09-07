<?php

$pw = array(
	"password",
	"mother"
);

$hashes = array(
	$pw[0] => password_hash($pw[0], PASSWORD_DEFAULT),
	$pw[0] . "2" => password_hash($pw[0], PASSWORD_DEFAULT),
	$pw[1] => password_hash($pw[1], PASSWORD_DEFAULT),
	$pw[1] . "2" => password_hash($pw[1], PASSWORD_DEFAULT),
);

echo "<pre>";
print_r($hashes);
echo "</pre>";