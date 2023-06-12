<?php
 namespace App\Backup\Rclone; class DropboxConfigTemplate extends ConfigTemplate { private const TYPE = "\144\162\x6f\x70\x62\157\170"; private array $defaultSettings = ["\x74\171\160\145" => self::TYPE]; public function __construct() { $this->addSettings($this->defaultSettings); } public function setToken(string $token) : void { $this->setSetting("\x74\157\153\145\x6e", $token); } }
