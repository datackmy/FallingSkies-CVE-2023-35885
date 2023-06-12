<?php
 namespace App\Site\Ssl\LetsEncrypt; class DomainValidationException extends \Exception { private array $validationErrors = []; public function setValidationErrors(array $validationErrors) : void { $this->validationErrors = $validationErrors; } public function getValidationErrors() : array { return $this->validationErrors; } }
