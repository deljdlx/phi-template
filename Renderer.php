<?php

namespace Phi\Template;


class Renderer implements \Phi\Core\Interfaces\Renderer
{
    private $buffer = '';

    public function __construct($buffer = '')
    {
        $this->buffer = $buffer;
    }


    public function setBuffer($buffer)
    {
        $this->buffer = $buffer;
        return $this;
    }

    public function getBuffer()
    {
        return $this->buffer;
    }

    public function render()
    {
        return $this->buffer;
    }
}