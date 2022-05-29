<?php

namespace Luchki\Confirmation\Contracts;

interface ICodeGenerator
{
        public function generate(): string;
}