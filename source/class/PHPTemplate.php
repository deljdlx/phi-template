<?php


namespace Phi\Template;


use Phi\Traits\Collection;

class PHPTemplate
{


    use Collection;


    protected $template = null;
    protected $components = array();


    public function __construct($template = null)
    {
        $this->template = $template;
    }


    public function set($name, $value)
    {
        return $this->setVariable($name, $value);
    }

    public function &get($name)
    {
        return $this->getVariable($name);
    }


    public function render($template = null, $values = null)
    {
        if ($template !== null) {
            $this->template = $template;
        }

        if (is_array($values)) {
            $this->setVariables($values);
        }


        if (is_file($this->template) && realpath($this->template)) {


            ob_start();
            extract($this->getVariables());
            include($template);
            $buffer = ob_get_clean();
            return $buffer;

        } else {
            ob_start();
            extract($this->getVariables());
            eval('?>'.$this->template);
            $buffer = ob_get_clean();
            return $buffer;
        }
    }


    public function __toString()
    {
        $buffer = $this->render();
        return $buffer;
    }


    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }


    public function &__get($name)
    {
        return $this->get($name);
    }


}


