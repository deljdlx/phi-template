<?php

namespace Phi\Template\Traits;


Trait MustacheTemplate
{


    protected $compileMustache = true;


    public function disableMustache()
    {
        $this->compileMustache = false;
        return $this;
    }

    public function enableMustache()
    {
        $this->compileMustache = true;
        return $this;
    }


    public function compileMustache($buffer, $variables)
    {
        if ($this->compileMustache) {

            $mustacheEngine = new \Mustache_Engine();
            $compiled = $mustacheEngine->render($buffer, $variables);

            return $compiled;
        }

        return $buffer;

    }

}
