<?php
require "glorious.php";
$db = new GloriousDB("your host", "your hostname", "your passwd", "database");
$db->setTable("table");
$db->where(["NO" => "2"]);
$res = $db->find(["id", "email"]);
print_r($res);
$db->destroy();
?>