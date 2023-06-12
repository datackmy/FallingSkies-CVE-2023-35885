<?php
 namespace App\Site\Ssl; abstract class Key { protected ?string $keyPEM = null; public function __construct(string $keyPEM) { $this->keyPEM = $keyPEM; } public function getPEM() : ?string { return $this->keyPEM; } public abstract function getResource(); }
