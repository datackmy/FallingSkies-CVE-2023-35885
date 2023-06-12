<?php
 namespace App\Site\Nginx\Vhost; use App\Site\Nginx\Vhost\Processor\ReverseProxyUrl as ReverseProxyUrlProcessor; class ReverseProxyTemplate extends Template { protected function init() : void { goto Ee444; bb240: $reverseProxyProcessor = new ReverseProxyUrlProcessor(); goto cdd47; Ee444: parent::init(); goto bb240; cdd47: $this->addProcessor($reverseProxyProcessor); goto C29dc; C29dc: } }
