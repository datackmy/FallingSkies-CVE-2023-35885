<?php
 namespace App\Api; class Error { private ?string $message = null; private array $data = []; public function getMessage() : string { return $this->message; } public function setMessage(string $message) : void { $this->message = $message; } public function getData() : array { return $this->data; } public function setData($key, $value) : void { $this->data[$key] = $value; } }
