<?php

namespace DoatKolom\Ui;

use DoatKolom\Ui\Components\Pulse;
use DoatKolom\Ui\Components\Tab;
use DoatKolom\Ui\Utils\Common;

abstract class Layout
{
    protected $data = [];

    use Common;
    use Pulse;
    use Tab;

    abstract public function enqueue_script();
}
