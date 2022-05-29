<?php

namespace Luchki\Confirmation\CodeConfirmation\Contracts;

interface ICodeGenerator
{
        public function generate(): string;
}