<?php

require_once 'bootstrap.php';

$ThejournalApi = new ThejournalApi;
$result = $ThejournalApi->Execute($_SERVER["REQUEST_URI"]);

var_dump($result);
