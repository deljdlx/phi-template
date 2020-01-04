<?php


namespace Phi\Template;


use Phi\FileSystem\File;
use Phi\Traits\Collection;
use Phi\Traits\Introspectable;


use Phi\Template\Interfaces\Renderer;
use Phi\Template\Traits\MustacheTemplate;



class Template implements Renderer
{

    use Collection;
    use Introspectable;
    use MustacheTemplate;


    protected $template = null;
    protected $components = array();

    /**
     * @var File
     */
    private $templateFile;


    public function __construct($template = null)
    {
        if($template !== null) {
            $this->template = $template;
        }
    }



    public function loadFile($file)
    {
        if(!$file instanceof File) {
            $file = new File($file);
        }

        $this->templateFile = $file;
        return $this;
    }

    public function loadTemplate($template)
    {
        $this->template = $template;
        return $this;
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

    protected function display($variableName, $escapeHTML = true) {

        if($this->offsetExists($variableName)) {

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


    /**
     * @return string
     */
    public function render()
    {
        if($this->templateFile) {
            return $this->renderFile($this->templateFile);
        }
        else {
            return $this->renderString($this->template);
        }
    }


    /**
     * @param $file
     * @param array $variables
     * @return string
     */
    public function obInclude($file, array $variables = array())
    {
        ob_start();

        extract($variables);
        include($file);

        $output = ob_get_clean();

        $this->compileMustache($output, $this->getVariables());

        return $output;
    }


    public function __toString()
    {
        $buffer = $this->render();
        return $buffer;
    }


    /**
     * @param $string
     * @return string
     */
    public function renderString($string) {
        ob_start();
        extract($this->getVariables());
        eval('?>' . $string);
        $buffer = ob_get_clean();
        $buffer = $this->compileMustache($buffer, $this->getVariables());
        return $buffer;
    }


    /**
     * @return string
     */
    public function renderFile()
    {
        return $this->obInclude($this->templateFile->normalize(), $this->getVariables());
    }


}


