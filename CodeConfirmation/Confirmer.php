<?php

namespace Luchki\Confirmation\CodeConfirmation;

use Luchki\Confirmation\CodeConfirmation\contracts\ICodeConfirmation;
use Luchki\Confirmation\CodeConfirmation\contracts\ICodeConfirmationRepository;
use Luchki\Confirmation\CodeConfirmation\contracts\ICodeGenerator;
use Luchki\Confirmation\CodeConfirmation\contracts\IConfirmationSubscriber;

class Confirmer
{
        /** @var ICodeConfirmationRepository */
        private $confirmation_repository;
        /** @var int */
        private $expire_timeout_seconds = 300;
        /** @var ICodeGenerator */
        private $code_generator;
        /** @var IConfirmationSubscriber[] */
        private $subscribers;

        /**
         * @param ICodeConfirmationRepository $confirmation_repository
         * @param IConfirmationSubscriber[]   $subscribers
         * @param ICodeGenerator|null         $code_generator
         */
        public function __construct(
                ICodeConfirmationRepository $confirmation_repository,
                array                       $subscribers = [],
                ?ICodeGenerator             $code_generator = null
        ) {
                $this->confirmation_repository = $confirmation_repository;
                $this->code_generator = $code_generator ?? new NumberCodeGenerator();
                $this->subscribers = $subscribers;
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

        private function confirmationExists(string $identity):bool {
                return $this->confirmation_repository->getConfirmation($identity) !== null;
        }

        private function getConfirmationFromRepo(string $identity): ?ICodeConfirmation {
                return $this->confirmation_repository->getConfirmation($identity);
        }

        /**
         * @param string $identity
         * @return CodeConfirmationEntity
         */
        private function makeNewConfirmation(string $identity): CodeConfirmationEntity {
                $confirmation = (new CodeConfirmationEntity(
                        $identity,
                        $this->generateCode(),
                        $this->makeExpireTimestamp()
                ));
                $this->saveConfirmation($confirmation);
                $this->notifySubscribers($confirmation);
                return $confirmation;
        }

        public function expireConfirmation(string $identity): void {
                $confirmation = $this->getConfirmationFromRepo($identity);
                if ($confirmation !== null) {
                        $confirmation->setIsExpired();
                        $this->saveConfirmation($confirmation);
                }
        }

        private function notifySubscribers(ICodeConfirmation $confirmation): void {
                foreach ($this->subscribers as $subscriber) {
                        $subscriber->notify($confirmation);
                }
        }

        /**
         * @return int
         */
        private function makeExpireTimestamp(): int {
                return (new \DateTime())->getTimestamp() + $this->expire_timeout_seconds;
        }
}