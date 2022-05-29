<?php

namespace App\CodeConfirmation;

use App\CodeConfirmation\contracts\ICodeConfirmation;
use App\CodeConfirmation\contracts\ICodeConfirmationRepository;

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