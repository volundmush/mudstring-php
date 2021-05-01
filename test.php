<?php

require_once __DIR__ . "/src/MudString.php";
require_once __DIR__ . "/src/PennMUSH.php";


$t = PennMUSH::decode("Have some \002chru\003very red\002c/\003 text!\n");
print($t->render(true, true, true, true));

$t2 = PennMUSH::ansi_function("hc", "Dragons for everyone!");
print($t2->render(true, true, true, true));

?>
