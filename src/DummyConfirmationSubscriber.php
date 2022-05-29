<?php

namespace Luchki\Confirmation;

use Luchki\Confirmation\Contracts\ICodeConfirmation;
use Luchki\Confirmation\Contracts\IConfirmationSubscriber;

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