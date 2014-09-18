<?php
include "server.php";

$arr__ = array(
	'jsonrpc' => '2.0',
	'result' => null,
	'id' => "id",
	'toto' => 'aaa'
);
echo htmlspecialchars(json_encode($arr__), ENT_NOQUOTES);
?>