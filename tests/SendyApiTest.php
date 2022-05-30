<?php

declare(strict_types=1);

use D0nth4x\SendyApi\SendyApi;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class SendyApiTest extends TestCase
{
    private SendyApi $client;

    public function setUp(): void
    {
        $this->client = new SendyApi(
            $_SERVER['SENDY_SERVER_URI'],
            $_SERVER['SENDY_API_KEY'],
            $_SERVER['SENDY_LIST_ID'],
            [RequestOptions::VERIFY => false]
        );
    }

    public function testUnsubscribeAndUnsubscribe(): void
    {
        $faker = Faker\Factory::create();
        $email = $faker->email();

        $response = $this->client->subscribe($email);
        self::assertSame(200, $response->getStatusCode());

        $status = $this->client->getSubscriptionStatus($email);
        self::assertSame(SendyApi::SUBSCRIPTION_STATUS_SUBSCRIBED, $status);

        $response = $this->client->unsubscribe($email);
        self::assertSame(200, $response->getStatusCode());

        $status = $this->client->getSubscriptionStatus($email);
        self::assertSame(SendyApi::SUBSCRIPTION_STATUS_UNSUBSCRIBED, $status);
    }

    public function testGetSubscribersCount(): void
    {
        $result = $this->client->getSubscribersCount();
        self::assertIsInt($result);
        self::assertGreaterThanOrEqual(0, $result);
    }
}
