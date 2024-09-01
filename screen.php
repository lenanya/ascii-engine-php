<?php

class Screen {
    public $width;
    public $height;
    public $refresh_rate;
    private $default_char;
    private $frame;

    private $pixels;

    private $ascii_table = ' .,:;I!i><~+_-?][}{1)(|/tfjrxnuvczXYUJCLQ0OZmwqpdbkhao*#MW&8%B@$';
    
    function __construct($width, $height, $refresh_rate = 30, $default_char = 63) {
        $this->width = $width;
        $this->height = $height;
        $this->refresh_rate = $refresh_rate;
        $this->pixels = array();
        $this->default_char = $this->ascii_table[$default_char];
        $this->frame = 0;
        
        for ($y = 0; $y < $height; $y++) {
            $current_row = array();
            for ($x = 0; $x < $width; $x++) {
                $current_row[$x] = $this->default_char;
            }
            $this->pixels[$y] = $current_row;
        }
        readline_callback_handler_install('', function() { });
    }

    function draw() {
        for ($y = 0; $y < $this->height; $y++) {
            $current_line = '';
            for ($x = 0; $x < $this->width; $x++) {
                $current_line .= $this->get_pixel($x, $y);
            }
            echo $current_line . "\n";
        }
    }

    function set_pixel($x, $y, $b, $c = false) {
        if (!$c) {
            $char = $this->ascii_table[$b];
        } else {
            $char = $b;
        }
        $this->pixels[$y][$x] = $char;
    }

    function get_pixel($x, $y) {
        if ($x < $this->width && $y < $this->height) {            
            return $this->pixels[$y][$x];
        } else {
            throw new UnexpectedValueException('Values $x or $y outside of screen.');
        }
    }

    function clear_screen($b, $c = false) {
        $this->pixels = array();
        for ($y = 0; $y < $this->height; $y++) {
            $current_row = array();
            for ($x = 0; $x < $this->width; $x++) {
                if (!$c) {
                    $char = $this->ascii_table[$b];
                } else {
                    $char = $b;
                }
                $current_row[$x] = $char;
            }
            $this->pixels[$y] = $current_row;
        } 
    }

    function rect($x, $y, $w, $h, $b, $c = false) {
		for ($yp = $y; $yp < $y + $h; $yp++) {
			for ($xp = $x; $xp < $x + $w; $xp++) {
				$this->set_pixel($xp, $yp, $b, $c);
			}
		}
    }
	
	function next_frame() {
		$delay = floor(1000000 / $this->refresh_rate);
		usleep($delay);
        $this->frame = ($this->frame + 1) % $this->refresh_rate;
	}

    function get_keystate($key) {
        $r = array(STDIN);
        $w = null;
        $e = null;
        $n = stream_select($r, $w, $e, 0, 0);
        if ($n && in_array(STDIN, $r)) {
            $pressed = stream_get_contents(STDIN, 1);
        } else {
            $pressed = null;
        }
        if ($pressed == $key) {
            return true;
        }
        return false;
    }
}
