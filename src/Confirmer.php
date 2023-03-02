<?php

namespace Luchki\Confirmation;

use Luchki\Confirmation\Contracts\ICodeConfirmation;
use Luchki\Confirmation\Contracts\ICodeConfirmationRepository;
use Luchki\Confirmation\Contracts\ICodeGenerator;

class Confirmer
{
        /** @var ICodeConfirmationRepository */
        private $confirmation_repository;
        /** @var int */
        private $expire_timeout_seconds;
        /** @var ICodeGenerator */
        private $code_generator;

        /**
         * @param ICodeConfirmationRepository $confirmation_repository
         * @param ICodeGenerator|null         $code_generator
         */
        public function __construct(
                ICodeConfirmationRepository $confirmation_repository,
                ?ICodeGenerator             $code_generator = null,
                int                         $expire_timeout_seconds = 300
        ) {
                $this->expire_timeout_seconds = $expire_timeout_seconds;
                $this->confirmation_repository = $confirmation_repository;
                $this->code_generator = $code_generator ?? new NumberCodeGenerator();
        }

        public function getConfirmationCode(string $identity): string {
                if ($this->confirmationExists($identity)) {
                        $confirmation = $this->getConfirmationFromRepo($identity);
                        if ($confirmation->expired()) {
                                $confirmation = $this->makeNewConfirmation($identity);
                        }

                        return $confirmation->getCode();
                }

                return $this->makeNewConfirmation($identity)
                            ->getCode()
                ;
        }

        public function confirm(string $identity, string $code): bool {
                $confirmation = $this->confirmation_repository->getConfirmation($identity);

                return $confirmation !== null && !$confirmation->expired() && $confirmation->getCode() === $code;
        }

        private function generateCode(): string {
                return $this->code_generator->generate();
        }

        private function saveConfirmation(ICodeConfirmation $confirmation): void {
                $this->confirmation_repository->saveConfirmation($confirmation);
        }

        private function confirmationExists(string $identity): bool {
                return $this->confirmation_repository->getConfirmation($identity) !== null;
        }

        private function getConfirmationFromRepo(string $identity): ?ICodeConfirmation {
                return $this->confirmation_repository->getConfirmation($identity);
        }

        /**
         * @param string $identity
         * @return ConfirmationEntity
         */
        private function makeNewConfirmation(string $identity): ConfirmationEntity {
                $confirmation = (new ConfirmationEntity(
                        $identity,
                        $this->generateCode(),
                        $this->makeExpireTimestamp()
                ));
                $this->saveConfirmation($confirmation);

                return $confirmation;
        }

        public function expireConfirmation(string $identity): void {
                $confirmation = $this->getConfirmationFromRepo($identity);
                if ($confirmation !== null) {
                        $confirmation->setIsExpired();
                        $this->saveConfirmation($confirmation);
                }
        }

        /**
         * @return int
         */
        private function makeExpireTimestamp(): int {
                return (new \DateTime())->getTimestamp() + $this->expire_timeout_seconds;
        }
}