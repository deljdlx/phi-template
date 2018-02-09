<?php


namespace Phi\Template;


use Phi\Traits\Collection;
use Phi\Traits\Introspectable;

class PHPTemplate
{


    use Collection;
    use Introspectable;


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
            include($this->template);
            $buffer = ob_get_clean();
            return $buffer;

        }
        else {
            ob_start();
            extract($this->getVariables());
            eval('?>' . $this->template);
            $buffer = ob_get_clean();
            return $buffer;
        }
    }

    public function fragment($file, $shareVariables = false, $variables = array())
    {
        ob_start();
        if($shareVariables) {
            extract($this->getVariables());
        }
        extract($variables);
        include($this->getDefinitionFolder().'/'.$file);

        echo ob_get_clean();
        return $this;
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


