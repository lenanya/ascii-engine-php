<?php

require 'screen.php';

$speed = $argv[1] ?? 1;

$lines = $argv[2] ?? 40;
$cols = $argv[3] ?? 170;

$amount = $argv[4] ?? 1;

$s = new Screen($cols, $lines, 60, 0);

class Cube {
    public $x;
    public $y;
    public $speed_x;
    public $speed_y;
    public $char;

    function __construct($x, $y, $speed_x, $speed_y, $char, $lines, $cols) {
        $this->x = $x;
        $this->y = $y;
        $this->speed_x = $speed_x;
        $this->speed_y = $speed_y;
        $this->char = $char;
        $this->lines = $lines;
        $this->cols = $cols;
    }

    function move() {
        $this->x += $this->speed_x;
        $this->y += $this->speed_y;

        if ($this->y + floor($this->lines / 10) >= $this->lines || $this->y - 1 <= 0) {
            $this->speed_y *= -1;
            $this->char = rand(1, 63);
        }
        if ($this->x + floor(2 * $this->lines / 10) >= $this->cols || $this->x - 1 <= 0) {
            $this->speed_x *= -1;
            $this->char = rand(1, 63);

        }
    }

    function draw($s) {
	$h = floor($this->lines / 10);
        $s->rect(floor($this->x), floor($this->y), $h * 2, $h, $this->char);
    }
}


$cubes = array();

$r = (float)$lines / (float)$cols;

for ($i = 0; $i < $amount; $i++) {
	$cubes[] = new Cube(rand(1, floor($cols * 0.9)), rand(1, floor($lines * 0.9)), (rand(-$speed, $speed) + 0.1) * $lines / 60, (rand(-$speed, $speed) + 0.1) * $r * $lines / 60, rand(1, 63), $lines, $cols);
}




while (true) {   
    system('clear');
    $s->clear_screen(0);
    
    foreach ($cubes as $c) {
        $c->move();
        $c->draw($s);
    }
    $s->draw();
    $s->next_frame();
}
