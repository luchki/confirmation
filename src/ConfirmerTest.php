<?php

namespace unit;

use Luchki\Confirmation\CodeConfirmationArrayRepository;
use Luchki\Confirmation\Confirmer;
use Luchki\Confirmation\DummyConfirmationSubscriber;
use Luchki\Confirmation\Contracts\ICodeConfirmationRepository;
use Codeception\Test\Unit;

class ConfirmerTest extends Unit
{
        public function testInit() {
                $this->getConfirmer();
        }

        public function testGetConfirmationCode() {
                $confirmer = $this->getConfirmer();
                $code = $confirmer->getConfirmationCode('test');

                $this->assertNotNull($code);
        }

        public function testValidConfirmationCode() {
                $confirmer = $this->getConfirmer();
                $code = $confirmer->getConfirmationCode('test');
                $this->assertTrue($confirmer->confirm('test', $code));
        }

        public function testInvalidConfirmationCode() {
                $confirmer = $this->getConfirmer();
                $code = $confirmer->getConfirmationCode('test');
                $wrong_code = 1111111111;
                $this->assertFalse($confirmer->confirm('test', $wrong_code));
                $this->assertTrue($confirmer->confirm('test', $code));
        }

        public function testNotExistedConfirmation() {
                $confirmer = $this->getConfirmer();
                $code = $confirmer->getConfirmationCode('test');

                $this->assertTrue($code === $confirmer->getConfirmationCode('test'));
                $this->assertFalse($code === $confirmer->getConfirmationCode('babas'));

        }

        public function testCodeIsSameForSameIdendity() {
                $confirmer = $this->getConfirmer();
                $code = $confirmer->getConfirmationCode('test');
                $code2 = $confirmer->getConfirmationCode('test');

                $this->assertEquals($code, $code2);
        }

        public function testCanConfirm() {
                $confirmer = $this->getConfirmer();
                $code = $confirmer->getConfirmationCode('test');

                $this->assertTrue($confirmer->confirm('test', $code));
        }

        public function testCanNotConfirmWithExpiredConfirmation() {
                $confirmer = $this->getConfirmer();
                $code = $confirmer->getConfirmationCode('test');
                $confirmer->expireConfirmation('test');
                $this->assertFalse($confirmer->confirm('test', $code));

                $new_code = $confirmer->getConfirmationCode('test');
                $this->assertTrue($confirmer->confirm('test', $new_code));
        }

        public function testNewCodeAfterExpire() {
                $confirmer = $this->getConfirmer();
                $code = $confirmer->getConfirmationCode('test');
                $confirmer->expireConfirmation('test');

                $new_code = $confirmer->getConfirmationCode('test');

                $this->assertNotEquals($code, $new_code);
        }

        public function testNotifications() {
                $dummy_subscriber = new DummyConfirmationSubscriber();
                $confirmer = new Confirmer(
                        new CodeConfirmationArrayRepository(),
                        [$dummy_subscriber]
                );
                $code = $confirmer->getConfirmationCode('test');
                $code2 = $confirmer->getConfirmationCode('test2');

                $this->assertEquals($code, $dummy_subscriber->getCreatedConfirmations()['test']);
                $this->assertEquals($code2, $dummy_subscriber->getCreatedConfirmations()['test2']);
        }

        /**
         * @group database
         */
        public function testDBRepositoryConfirmation() {
                $dummy_subscriber = new DummyConfirmationSubscriber();
                $repo = new ConfirmationRepository();
                $confirmer = new Confirmer(
                        $repo,
                        [$dummy_subscriber]
                );
                $id_visitor = 10374083;
                $code = $confirmer->getConfirmationCode($id_visitor);
                $confirmation = $repo->getConfirmation($id_visitor);
                $confirmation->setIsExpired();
                $this->assertEquals($code, $confirmation->getCode());
        }

        /**
         * @group database
         */
        public function testDBRepositoryExpireConfirmation() {
                $repo = new ConfirmationRepository();
                $confirmer = $this->getConfirmer($repo);
                $code = $confirmer->getConfirmationCode('test');
                $confirmer->expireConfirmation('test');

                $this->assertFalse($confirmer->confirm('test', $code));

                $new_code = $confirmer->getConfirmationCode('test');
                $this->assertNotEquals($code, $new_code);
                $this->assertTrue($confirmer->confirm('test', $new_code));
        }


        private function getConfirmer(?ICodeConfirmationRepository $repo = null) {
                return new Confirmer($repo ?? new CodeConfirmationArrayRepository());
        }
}