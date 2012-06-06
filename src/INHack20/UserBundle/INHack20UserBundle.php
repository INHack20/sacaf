<?php

namespace INHack20\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class INHack20UserBundle extends Bundle
{
    public function getParent(){
        return 'FOSUserBundle';
    }
}
