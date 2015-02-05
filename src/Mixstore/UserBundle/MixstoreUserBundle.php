<?php

namespace Mixstore\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MixstoreUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
