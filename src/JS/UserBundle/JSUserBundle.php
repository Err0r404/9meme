<?php

namespace JS\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JSUserBundle extends Bundle {
    public function getParent() {
        return 'FOSUserBundle';
    }
}
