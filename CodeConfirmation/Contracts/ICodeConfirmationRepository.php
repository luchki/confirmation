<?php

namespace Luchki\Confirmation\CodeConfirmation\Contracts;

interface ICodeConfirmationRepository
{
        public function getConfirmation(string $identity): ?ICodeConfirmation;

        public function saveConfirmation(ICodeConfirmation $confirmation): void;
}