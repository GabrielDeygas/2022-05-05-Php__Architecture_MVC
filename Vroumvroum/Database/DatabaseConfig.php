<?php

namespace Vroumvroum\Database;

interface DatabaseConfig
{
	public function getHost(): string;
	public function getName(): string;
	public function getUser(): string;
	public function getPass(): string;
}