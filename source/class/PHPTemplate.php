<?php


namespace Phi\Template;


use Phi\Core\Interfaces\Renderer;
use Phi\HTML\ViewComponent;
use Phi\Template\Traits\MustacheTemplate;
use Phi\Traits\Collection;
use Phi\Traits\Introspectable;

class PHPTemplate implements Renderer
{

    use MustacheTemplate;
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

        $output = ob_get_clean();

        $this->compileMustache($output, $this->getVariables());

        return $output;

        //include($this->getDefinitionFolder().'/'.$file);


    }




    public function __toString()
    {
        $buffer = $this->render();
        return $buffer;
    }


}


