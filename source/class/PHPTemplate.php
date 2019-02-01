<?php


namespace Phi\Template;


use Phi\HTML\ViewComponent;
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
        if($template !== null) {
            $this->template = $template;
        }

    }


    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }


    public function __get($name)
    {
        return $this->get($name);
    }


    public function set($name, $value)
    {
        return $this->setVariable($name, $value);
    }

    public function get($name)
    {
        return $this->getVariable($name);
    }

    protected function display($variableName, $escapeHTML = true, $variables = array()) {



        if($this->offsetExists($variableName)) {

            if($this->getVariable($variableName) instanceof ViewComponent) {
                foreach ($variables as $name => $value) {
                    $this->getVariable($variableName)->setVariable($name, $value);
                }
            }

            if($escapeHTML) {
                echo htmlentities($this->getVariable($variableName));
            }
            else {
                echo $this->getVariable($variableName);
            }
        }
        else {
            if($escapeHTML) {
                echo htmlentities($variableName);
            }
            else {
                echo $variableName;
            }
        }
    }



    public function render()
    {



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


    public function obInclude($file, $variables = array(), $shareVariables = false)
    {
        ob_start();
        if($shareVariables) {
            extract($this->getVariables());
        }
        extract($variables);
        include($file);
        //include($this->getDefinitionFolder().'/'.$file);

        return ob_get_clean();
    }




    public function __toString()
    {
        $buffer = $this->render();
        return $buffer;
    }


}


