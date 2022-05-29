<?php

namespace App\CodeConfirmation\contracts;

interface ICodeConfirmation
{
        public function getIdentity(): string;

        public function getCode(): string;

        public function expired(): bool;

        public function setIsExpired(): void;

        public function getExpireTimestamp(): int;
}