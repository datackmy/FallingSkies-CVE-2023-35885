<?php
 namespace App\Ufw\Firewall; class AllowTcpRule { private ?string $ip = null; private ?string $portRange = null; public function getIp() : ?string { return $this->ip; } public function setIp(string $ip) : void { $this->ip = $ip; } public function getPortRange() : ?string { return $this->portRange; } public function setPortRange(string $portRange) : void { $this->portRange = $portRange; } }
