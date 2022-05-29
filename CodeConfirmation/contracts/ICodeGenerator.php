<?php

namespace App\CodeConfirmation\contracts;

interface ICodeGenerator
{
        public function generate(): string;
}