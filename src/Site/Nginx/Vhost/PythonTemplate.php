<?php
 namespace App\Site\Nginx\Vhost; use App\Site\Nginx\Vhost\Processor\PythonAppPort as PythonAppPortProcessor; class PythonTemplate extends Template { protected function init() : void { goto E4a15; E4a15: parent::init(); goto E3fa7; E3fa7: $pythonAppPortProcessor = new PythonAppPortProcessor(); goto c4942; c4942: $this->addProcessor($pythonAppPortProcessor); goto Dd6bd; Dd6bd: } }
