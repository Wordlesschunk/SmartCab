<?php

declare(strict_types=1);

namespace App\Classes\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WLEDClient
{
    private const string STATE_ENDPOINT = '/json/state';

    public function __construct(
        private readonly HttpClientInterface $client
    ){
    }

    public function getState(string $ip): array
    {
        $response = $this->client->request(
            'GET',
            $this->buildUrl($ip, self::STATE_ENDPOINT)
        );

        return $response->toArray();
    }

    public function getInfo(string $ip): array
    {
        $response = $this->client->request(
            'GET',
            'http://' . $ip . '/json/info'
        );

        return $response->toArray();
    }

    public function powerOn(string $ip): int
    {
        $response = $this->client->request(
            'POST',
            $this->buildUrl($ip, self::STATE_ENDPOINT),
            ['json' => [
                'on' => true,
                'transition' => 0,
                'bri' => 255,
                'seg' => [
                    'start' => 0,
                    'stop' => 10,
                ]
            ]]
        );

        return $response->getStatusCode();
    }

    public function powerOff(string $ip): int
    {
        $response = $this->client->request(
            'POST',
            $this->buildUrl($ip, self::STATE_ENDPOINT),
            ['json' => [
                'on' => false,
                'transition' => 0,
                ]
            ]
        );

        return $response->getStatusCode();
    }

    public function setBrightness(string $ip, int $brightness): int
    {
        if ($brightness > 255) {
            throw new \InvalidArgumentException('Brightness must be between 0 and 255');
        }

        $response = $this->client->request(
            'POST',
            $this->buildUrl($ip, self::STATE_ENDPOINT),
            ['json' => ['bri' => $brightness]]
        );

        return $response->getStatusCode();
    }

    public function powerOnSingleLED(string $ip, int $ledId): int
    {
        // Build a full frame of "off"
        $pixels = array_fill(0, 10, [0, 0, 0]);

        // Turn on just one LED (red here)
        $pixels[$ledId] = [255, 0, 0];

        $response = $this->client->request(
            'POST',
            $this->buildUrl($ip, self::STATE_ENDPOINT),
            [
                'json' => [
                    'on' => true,
                    'bri' => 255,
                    'transition' => 0,
                    'seg' => [[
                        'id' => 0,
                        'i' => $pixels,
                    ]]
                ]
            ]
        );

        return $response->getStatusCode();

    }

    public function powerOnMultiLED(string $ip, array $ledIds): int
    {
        // Build a full frame of "off"
        $pixels = array_fill(0, 10, [0, 0, 0]);

        // Turn on just one LED (red here)
        foreach ($ledIds as $ledId)
        {
            $pixels[$ledId] = [255, 0, 0];
        }

        $response = $this->client->request(
            'POST',
            $this->buildUrl($ip, self::STATE_ENDPOINT),
            [
                'json' => [
                    'on' => true,
                    'bri' => 255,
                    'transition' => 0,
                    'seg' => [[
                        'id' => 0,
                        'i' => $pixels,
                    ]]
                ]
            ]
        );

        return $response->getStatusCode();

    }

    private function buildUrl(string $ip, string $endpoint): string
    {
        return sprintf('http://%s%s', $ip, $endpoint);
    }
}
