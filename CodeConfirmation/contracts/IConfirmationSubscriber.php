<?php

namespace App\CodeConfirmation\contracts;

interface IConfirmationSubscriber
{
        public function notify(ICodeConfirmation $confirmation): void;
}