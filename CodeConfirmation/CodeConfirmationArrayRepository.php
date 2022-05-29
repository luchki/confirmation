<?php

namespace Luchki\Confirmation\CodeConfirmation;

use Luchki\Confirmation\CodeConfirmation\contracts\ICodeConfirmation;
use Luchki\Confirmation\CodeConfirmation\contracts\ICodeConfirmationRepository;

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