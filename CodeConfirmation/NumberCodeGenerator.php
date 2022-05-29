<?php

namespace Luchki\Confirmation\CodeConfirmation;

use Luchki\Confirmation\CodeConfirmation\Contracts\ICodeGenerator;

class NumberCodeGenerator implements ICodeGenerator
{
        /** @var int */
        private $length;

        public function __construct(int $length = 4) {
                $this->length = $length;
        }

        public function generate(): string {
                $code = '';
                for ($i = 1; $i <= $this->length; $i++) {
                        $code .= random_int(0, 9);
                }

                return $code;
        }
}