<?php

namespace Luchki\Confirmation\CodeConfirmation\contracts;

interface IConfirmationSubscriber
{
        public function notify(ICodeConfirmation $confirmation): void;
}