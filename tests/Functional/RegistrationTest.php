<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Email;
use App\Tests\MailerTestLogger;

class RegistrationTest extends WebTestCase
{
    public function testRegistrationSendsEmail()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        // Get the logger service
        /** @var MailerTestLogger $logger */
        $logger = $container->get(MailerTestLogger::class);

        // Perform the registration
        $client->request('GET', '/inscription');
        $form = $client->submitForm('Register', [
            'registration_form[email]' => 'test@example.com',
            'registration_form[plainPassword]' => 'password123',
        ]);

        // Check if at least one email was sent
        $this->assertGreaterThan(0, count($logger->getEvents()));

        // Check the content of the email
        /** @var MessageEvent $event */
        $event = $logger->getEvents()[0];
        /** @var Email $email */
        $email = $event->getMessage();

        $this->assertInstanceOf(Email::class, $email);
        $this->assertSame('test@example.com', $email->getTo()[0]->getAddress());
        $this->assertSame('Please confirm your Email', $email->getSubject());
    }
}
