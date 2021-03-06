<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\CRMBundle\Tests\Controller;

use Librinfo\CRMBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleControllerTest extends WebTestCase
{
    private $datafixtures;
    private $client;

    public function init()
    {
        $this->client = static::createClient();

        $this->datafixtures = $this->client->getContainer()->getParameter('librinfo.crmbundle.datafixtures');
    }

    /**
     * testsAdd.
     */
    public function testsAdd()
    {
        $this->init();
        $role = new Role();
        $role->setName($this->datafixtures['role']['name']);

        $this->assertEquals($this->datafixtures['role']['name'], $role->getName());
    }
}
