<?php

namespace App\Service\Mcs;

use App\Exception\McsException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class McsClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $mcsBaseUrl,
        private string $mcsApiPrefix,
        private string $mcsApiToken,
    ) {}

    public function get(string $endpoint): array
    {
        return $this->request('GET', $endpoint);
    }

    public function post(string $endpoint, array $body = []): array
    {
        return $this->request('POST', $endpoint, $body);
    }

    public function patch(string $endpoint, array $body = []): array
    {
        return $this->request('PATCH', $endpoint, $body);
    }

    public function request(string $method, string $endpoint, array $body = []): array
    {
        $url = rtrim($this->mcsBaseUrl, '/') . '/' . trim($this->mcsApiPrefix, '/') . '/' . ltrim($endpoint, '/');

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->mcsApiToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        if (!empty($body)) {
            $options['json'] = $body;
        }

        $response = $this->httpClient->request($method, $url, $options);

        return $this->handleResponse($response);
    }

    private function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode === 204) {
            return [];
        }

        $content = $response->getContent(false);
        $data = json_decode($content, true);

        if ($statusCode >= 200 && $statusCode < 300) {
            return is_array($data) ? $data : [];
        }

        $error = $data['error'] ?? null;

        throw new McsException(
            message: $error['message'] ?? 'Unknown MCS error.',
            codeValue: $error['code'] ?? null,
            details: $error['details'] ?? null,
            requestId: $error['requestId'] ?? null,
            statusCode: $statusCode
        );
    }
}

?>