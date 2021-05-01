<?php

require_once __DIR__ . "/src/mudstring.php";
require_once __DIR__ . "/src/pennmush.php";

$t = penn_decode("Have some \002chru\003very red\002c/\003 text!\n");
print($t->render(true, true, true, true));

$t2 = penn_ansi_function("hc", "Dragons for everyone!");
print($t2->render(true, true, true, true));

?>