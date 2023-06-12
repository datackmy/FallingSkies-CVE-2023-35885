<?php
 namespace App\Site\Nginx\Vhost; use App\Site\Nginx\Vhost\Processor\NodejsAppPort as NodejsAppPortProcessor; class NodejsTemplate extends Template { protected function init() : void { goto ef4be; Cb8c0: $this->addProcessor($nodejsAppPortProcessor); goto B3896; a81ba: $nodejsAppPortProcessor = new NodejsAppPortProcessor(); goto Cb8c0; ef4be: parent::init(); goto a81ba; B3896: } }
