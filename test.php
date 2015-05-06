<?php
require "glorious.php";
$db = new GloriousDB("127.0.0.1", "root", "******", "userinfo");
$db->setTable("info");
$db->where(["NO" => "4"]);
$res = $db->find(["id", "email"]);
print_r($res);
$db->destroy();
?>