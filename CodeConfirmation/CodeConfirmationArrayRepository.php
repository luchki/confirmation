<?php

namespace Luchki\Confirmation\CodeConfirmation;

use Luchki\Confirmation\CodeConfirmation\Contracts\ICodeConfirmation;
use Luchki\Confirmation\CodeConfirmation\Contracts\ICodeConfirmationRepository;

class CodeConfirmationArrayRepository implements ICodeConfirmationRepository
{
        /** @var ICodeConfirmation[] */
        private $confirmations = [];

        public function getConfirmation(string $identity): ?ICodeConfirmation {
                return $this->confirmations[$identity] ?? null;
        }

        public function saveConfirmation(ICodeConfirmation $confirmation): void {
                $this->confirmations[$confirmation->getIdentity()] = $confirmation;
        }
}