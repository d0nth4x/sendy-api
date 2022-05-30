<?php

namespace D0nth4x\SendyApi;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;

class SendyApiClient
{
    public const SUBSCRIPTION_STATUS_SUBSCRIBED = 'Subscribed';
    public const SUBSCRIPTION_STATUS_UNSUBSCRIBED = 'Unsubscribed';
    public const SUBSCRIPTION_STATUS_UNCONFIRMED = 'Unconfirmed';
    public const SUBSCRIPTION_STATUS_BOUNCED = 'Bounced';
    public const SUBSCRIPTION_STATUS_SOFT_BOUNCED = 'Soft bounced';
    public const SUBSCRIPTION_STATUS_COMPLAINED = 'Complained';

    private Client $httpClient;
    private string $sendyServerUri;
    private string $apiKey;
    private string $listId;

    public function __construct(string $sendyServerUri, string $apiKey, string $listId, array $curlOptions = [])
    {
        $this->apiKey = $apiKey;
        $this->listId = $listId;
        $curlOptions['base_uri'] = $sendyServerUri;
        $this->httpClient = new Client($curlOptions);
    }

    public function getSendyServerUri(): string
    {
        return $this->sendyServerUri;
    }

    public function getListId(): string
    {
        return $this->listId;
    }

    public function setListId(string $listId): self
    {
        $this->listId = $listId;

        return $this;
    }

    public function subscribe(string $email, ?string $name = null, ?string $country = null, ?string $ipAddress = null, ?string $refferer = null, ?bool $gdpr = null): Response
    {
        return $this->httpClient->post('/subscribe', [RequestOptions::FORM_PARAMS => [
            'api_key' => $this->apiKey,
            'list' => $this->listId,
            'email' => $email,
            'name' => $name,
            'country' => $country,
            'ipaddress' => $ipAddress,
            'refferer' => $refferer,
            'gdpr' => $gdpr,
            'boolean' => true,
        ]]);
    }

    public function unsubscribe(string $email): Response
    {
        return $this->httpClient->post('/unsubscribe', [RequestOptions::FORM_PARAMS => [
            'email' => $email,
            'list' => $this->listId,
            'boolean' => 'true',
        ]]);
    }

    public function deleteSubscriber(string $email): Response
    {
        return $this->httpClient->post('/api/subscribers/delete.php', [RequestOptions::FORM_PARAMS => [
            'api_key' => $this->apiKey,
            'list_id' => $this->listId,
            'email' => $email,
        ]]);
    }

    public function getSubscriptionStatus(string $email): string
    {
        return $this->httpClient->post('/api/subscribers/subscription-status.php', [RequestOptions::FORM_PARAMS => [
            'api_key' => $this->apiKey,
            'list_id' => $this->listId,
            'email' => $email,
        ]])->getBody()->getContents();
    }

    public function getSubscribersCount(): int
    {
        return (int) $this->httpClient->post('/api/subscribers/active-subscriber-count.php', [RequestOptions::FORM_PARAMS => [
            'api_key' => $this->apiKey,
            'list_id' => $this->listId,
        ]])->getBody()->getContents();
    }
}
