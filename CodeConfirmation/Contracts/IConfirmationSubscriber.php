<?php

namespace Luchki\Confirmation\CodeConfirmation\Contracts;

interface IConfirmationSubscriber
{
        public function notify(ICodeConfirmation $confirmation): void;
}