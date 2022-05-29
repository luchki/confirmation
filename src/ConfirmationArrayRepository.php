<?php

namespace Luchki\Confirmation\CodeConfirmation;

use Luchki\Confirmation\Contracts\ICodeConfirmation;
use Luchki\Confirmation\Contracts\ICodeConfirmationRepository;

class ConfirmationArrayRepository implements ICodeConfirmationRepository
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