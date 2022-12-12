<?php

namespace DoatKolom\Ui;

use DoatKolom\Ui\Components\Pulse;
use DoatKolom\Ui\Components\Tab;
use DoatKolom\Ui\Utils\Common;

abstract class Layout
{
    protected $data = [];

    use Pulse;

    abstract public function enqueue_script();
}
