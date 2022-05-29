<?php

namespace Luchki\Confirmation\Contracts;

interface IConfirmationSubscriber
{
        public function notify(ICodeConfirmation $confirmation): void;
}