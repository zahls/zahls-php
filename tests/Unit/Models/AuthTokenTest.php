<?php

use Zahls\CommunicationAdapter\CurlCommunication;
use Zahls\Models\Request\AuthToken;
use Zahls\Models\Response\AuthToken as ResponseAuthToken;
use Zahls\ZahlsException;

it('Success response - 200', function () {
    $mockCommunicator = new class() extends CurlCommunication {
        public function requestApi(string $apiUrl, $params = [], $method = 'POST', $httpHeader = []): array
        {
            return getMockResponse(
                200,
                [
                    ['authToken' => 'fake-token'],
                ],
                'Test Message'
            );
        }
    };
    $zahls = new \Zahls\Zahls('demo', 'demo', $mockCommunicator::class);
    $authToken = new AuthToken();
    $authToken->setUserId('1');

    $response = $zahls->create($authToken);
    expect($response->getAuthToken())->toBe('fake-token')
        ->and($response)->toBeInstanceOf(ResponseAuthToken::class);
});

it('Exception response - 400', function () {
    $mockCommunicator = new class() extends CurlCommunication {
        public function requestApi(string $apiUrl, $params = [], $method = 'POST', $httpHeader = []): array
        {
            return getMockResponse(400);
        }
    };
    $zahls = new \Zahls\Zahls('demo', 'demo', $mockCommunicator::class);
    $authToken = new AuthToken();
    $authToken->setUserId('1');
    $zahls->create($authToken);
})->throws(ZahlsException::class);

it('Exception response - 401', function () {
    $mockCommunicator = new class() extends CurlCommunication {
        public function requestApi(string $apiUrl, $params = [], $method = 'POST', $httpHeader = []): array
        {
            return getMockResponse(401);
        }
    };
    $zahls = new \Zahls\Zahls('demo', 'demo', $mockCommunicator::class);
    $authToken = new AuthToken();
    $authToken->setUserId('1');
    $zahls->create($authToken);
})->throws(ZahlsException::class);

it('Exception response - 403', function () {
    $mockCommunicator = new class() extends CurlCommunication {
        public function requestApi(string $apiUrl, $params = [], $method = 'POST', $httpHeader = []): array
        {
            return getMockResponse(403, [], '');
        }
    };
    $zahls = new \Zahls\Zahls('demo', 'demo', $mockCommunicator::class);
    $authToken = new AuthToken();
    $authToken->setUserId('1');
    $zahls->create($authToken);
})->throws(ZahlsException::class);

it('Exception response - 404', function () {
    $mockCommunicator = new class() extends CurlCommunication {
        public function requestApi(string $apiUrl, $params = [], $method = 'POST', $httpHeader = []): array
        {
            return getMockResponse(404);
        }
    };
    $zahls = new \Zahls\Zahls('demo', 'demo', $mockCommunicator::class);
    $authToken = new AuthToken();
    $authToken->setUserId('1');
    $zahls->create($authToken);
})->throws(ZahlsException::class);

it('Exception response - 500', function () {
    $mockCommunicator = new class() extends CurlCommunication {
        public function requestApi(string $apiUrl, $params = [], $method = 'POST', $httpHeader = []): array
        {
            return getMockResponse(500);
        }
    };
    $zahls = new \Zahls\Zahls('demo', 'demo', $mockCommunicator::class);
    $authToken = new AuthToken();
    $authToken->setUserId('1');
    $zahls->create($authToken);
})->throws(ZahlsException::class);

it('Exception response - 503', function () {
    $mockCommunicator = new class() extends CurlCommunication {
        public function requestApi(string $apiUrl, $params = [], $method = 'POST', $httpHeader = []): array
        {
            return getMockResponse(503, [], 'test', 'test');
        }
    };
    $zahls = new \Zahls\Zahls('demo', 'demo', $mockCommunicator::class);
    $authToken = new AuthToken();
    $authToken->setUserId('1');
    $zahls->create($authToken);
})->throws(ZahlsException::class);

it('Success response - 201', function () {
    $mockCommunicator = new class() extends CurlCommunication {
        public function requestApi(string $apiUrl, $params = [], $method = 'POST', $httpHeader = []): array
        {
            return getMockResponse(
                201,
                [
                    ['authToken' => 'fake-token'],
                ],
                'Test Message',
                'Test Reason'
            );
        }
    };
    $zahls = new \Zahls\Zahls('demo', 'demo', $mockCommunicator::class);
    $authToken = new AuthToken();
    $authToken->setUserId('1');

    $response = $zahls->create($authToken);
    expect($response->getAuthToken())->toBe('fake-token');
});
