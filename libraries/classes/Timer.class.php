<?php

/*
 * Class Timer
 *
 * might be useful for profiling; Usage:
 *
 * $timer = new Timer();
 * foo();
 * $timer->stop();
 *
 * Default settings will immediately start the timer when created and print the result when stopped.
 *
 */


class Timer
{
    private $begin;
    private $end;
    private $name;

    public function __construct($name, $start=true)
    {
        $this->name = $name;
        if($start) $this->start();
    }

    public function start()
    {
        $this->begin = microtime(true);
    }

    public function stop($display=true)
    {
        $this->end = microtime(true);
        if($display) $this->display();
    }

    public function display()
    {
        if($this->begin != 0 && $this->end != 0)
        {
            echo 'Timer ' . $this->name . ' duration: ' . ($this->end - $this->begin) . "\n<br />";
        }
        else
        {
            echo 'Not ready!';
        }
    }

    public function reset()
    {
        $this->begin = null;
        $this->end   = null;
    }
}
