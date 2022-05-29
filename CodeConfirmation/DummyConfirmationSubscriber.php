<?php

namespace Luchki\Confirmation\CodeConfirmation;

use Luchki\Confirmation\CodeConfirmation\contracts\ICodeConfirmation;
use Luchki\Confirmation\CodeConfirmation\contracts\IConfirmationSubscriber;

class DummyConfirmationSubscriber implements IConfirmationSubscriber
{
        /** @var string[] */
        private $created_confirmations = [];

        public function notify(ICodeConfirmation $confirmation): void {
                $this->created_confirmations[$confirmation->getIdentity()] = $confirmation->getCode();
        }

        /**
         * @return string[]
         */
        public function getCreatedConfirmations(): array {
                return $this->created_confirmations;
        }
}