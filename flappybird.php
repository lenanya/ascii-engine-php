<?php

require 'screen.php';

$s = new Screen(170, 40, 30, 0);

class Bird {
    public $x = 5;
    public $y = 18;
    public $w = 8;
    public $h = 4;
    public $speed = 0;
    public $g = 0.1;

    function move() {
        if ($this->speed < 1) {
            $this->speed += $this->g;
        }
        $this->y += $this->speed;
    }

    function jump() {
        $this->speed = -1;
    }

    function draw($s) {
        $s->rect($this->x, $this->y, $this->w, $this->h, 63);
    }
}

class Pipe {
    public $x = 140;
    public $y = 10;
    public $gap = 15;
    public $speed = 0.5;

    function move() {
        if ($this->x >= -8) {
            $this->x -= $this->speed;
        } else {
            $this->x = 180;
            $this->y = rand(5, 20);
            $this->speed += 0.1;
        }
    }

    function draw($s) {
        $s->rect($this->x, 0, 8, $this->y, 60);
        $s->rect($this->x, $this->y + $this->gap, 8, 40 - $this->y + $this->gap, 60);
    }
}

$bird = new Bird;
$pipe = new Pipe;

$text = str_split('Press Space to Begin');
$score_text = 'Score: ';
$score = 0;

$running = false;
while (!$running) {
    system('clear');
    $s->clear_screen('-', true);
    if ($s->get_keystate(' ')) {
        $running = true;
    }
    $bird->draw($s);
    $pipe->draw($s);

    foreach ($text as $i => $ch) {
        $s->set_pixel(170/2-10+$i, 20, $ch, true);
    }
    
    
    $s->draw();
    $s->next_frame();
}

while ($running) {
    system('clear');
    $s->clear_screen(0);
    
    if ($s->get_keystate(' ')) {
        $bird->jump();
    }
    
    $bird->move();
    $pipe->move();

    if ($bird->y < 0 || $bird->y > 35) {
        $running = false;
    }

    if ($bird->x + 8 > $pipe->x && $bird->x < $pipe->x + 8) {
        if ($bird-> y < $pipe->y || $bird->y + 4 > $pipe->y + $pipe->gap) {
            $running = false;
        }
    }
    
    $bird->draw($s);
    $pipe->draw($s);

    $score += 1;
    
    $s->draw();
    $s->next_frame();
}

system('clear');

$s->clear_screen(0);

$score_text = 'Score: ' . $score;

$score_text = str_split($score_text);

$score_text_len = count($score_text);

$pos = 170 / 2 - floor($score_text_len / 2);

foreach ($score_text as $i => $ch) {
    $s->set_pixel($pos + $i, 20, $ch, true);

}
$s->draw();
