<?php

declare(strict_types=1);

namespace App\Tests\Controller\Attendee;

use App\Entity\Attendee;
use App\Repository\AttendeeRepository;
use App\Tests\ApiTestCase;

class CreateControllerTest extends ApiTestCase
{
    public function test_it_should_create_an_attendee(): void
    {
        $attendeesBefore = static::getContainer()->get(AttendeeRepository::class)->findAll();
        static::assertCount(0, $attendeesBefore);

        $this->browser->request('POST', '/attendees', [], [], ['HTTP_Authorization' => 'Bearer '.$this->getUserToken()],
            <<<'EOT'
{
    "firstname": "Paul",
	"lastname": "Paulsen",
	"email": "paul@paulsen.de"
}
EOT
        );

        static::assertResponseStatusCodeSame(201);
        static::assertResponseHasHeader('Content-Type');
        static::assertResponseHasHeader('Location');

        $attendeesAfter = static::getContainer()->get(AttendeeRepository::class)->findAll();
        static::assertCount(1, $attendeesAfter);

        /** @var Attendee $expectedAttendee */
        $expectedAttendee = $attendeesAfter[0];
        static::assertSame('Paul', $expectedAttendee->getFirstname());
        static::assertSame('Paulsen', $expectedAttendee->getLastname());
        static::assertSame('paul@paulsen.de', $expectedAttendee->getEmail());
    }

    /**
     * @dataProvider provideUnprocessableAttendeeData
     */
    public function test_it_should_throw_an_UnprocessableEntityHttpException(string $requestBody): void
    {
        $this->browser->request('POST', '/attendees', [], [], ['HTTP_Authorization' => 'Bearer '.$this->getUserToken()], $requestBody);

        static::assertResponseStatusCodeSame(422);

        $this->assertMatchesJsonSnapshot($this->browser->getResponse()->getContent());
    }

    public static function provideUnprocessableAttendeeData(): \Generator
    {
        yield 'no data' => [''];
        yield 'empty data' => ['{}'];
        yield 'wrong json one' => ['{'];
        yield 'wrong json two' => ['}'];
        yield 'missing firstname' => ['{"lastname": "Paulsen", "email": "paul@paulsen.de"}'];
        yield 'missing lastname' => ['{"firstname": "Paul", "email": "paul@paulsen.de"}'];
        yield 'missing email' => ['{"firstname": "Paul", "lastname": "Paulsen"}'];
        yield 'wrong email' => ['{"firstname": "Paul", "lastname": "Paulsen", "email": "paulpaulsende"}'];
    }
}
