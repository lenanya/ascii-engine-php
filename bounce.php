<?php

require 'screen.php';

$s = new Screen(50, 45, 60, 0);

$speed = 0;
$y = 10;
$vel = 1;

while (true) {
    system('clear');
    $s->clear_screen(0);

    if ($speed < 5) {
        $speed += $vel;
    }

    $y += $speed;

    if ($y + 5 >= 45) {
        $speed *= -1;
        $y = 39;
    }

    $s->rect(10, floor($y), 5, 5, 63);
    $s->draw();
    $s->next_frame();
}
