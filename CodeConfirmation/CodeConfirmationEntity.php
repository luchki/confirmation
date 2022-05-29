<?php

namespace Luchki\Confirmation\CodeConfirmation;

use Luchki\Confirmation\CodeConfirmation\contracts\ICodeConfirmation;

class CodeConfirmationEntity implements ICodeConfirmation
{
        /** @var string */
        private $identity;
        /** @var string */
        private $code;
        /** @var int */
        private $expire_timestamp;

        public function __construct(string $identity, string $code, int $expire_timestamp) {
                $this->identity = $identity;
                $this->code = $code;
                $this->expire_timestamp = $expire_timestamp;
        }

        public function getIdentity(): string {
                return $this->identity;
        }

        public function getCode(): string {
                return $this->code;
        }

        public function expired(): bool {
                return (new \DateTime())->getTimestamp() > $this->expire_timestamp;
        }

        public function setIsExpired(): void {
                $this->expire_timestamp = (new \DateTime())->getTimestamp() - 1000;
        }

        public function getExpireTimestamp(): int {
                return $this->expire_timestamp;
        }
}