<?php

namespace Luchki\Confirmation\CodeConfirmation\contracts;

interface ICodeGenerator
{
        public function generate(): string;
}