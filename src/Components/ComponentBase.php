<?php

namespace DoatKolom\Ui\Components;

abstract class ComponentBase
{
    public function __construct()
    {
        $this->setIdentifiers();
    }

    public function setIdentifiers()
    {}

    public function render( array $settingArgs = [], array $items = [] )
    {
        $this->start( $settingArgs, $items );
        $this->content();
        $this->end();
    }

    abstract public function start( array $settingArgs, array $items );

    abstract public function content();

    abstract public function end();
}
