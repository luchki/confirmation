<?php

namespace Luchki\Confirmation\CodeConfirmation;

use Luchki\Confirmation\CodeConfirmation\Contracts\ICodeConfirmation;
use Luchki\Confirmation\CodeConfirmation\Contracts\IConfirmationSubscriber;

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