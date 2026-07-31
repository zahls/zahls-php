<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Zahls\CommunicationAdapter\GuzzleCommunication;
use Zahls\Communicator;
use Zahls\Zahls;

it('can create a GuzzleCommunication object', function () {
    $mock = new MockHandler([
        new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['authToken' => 'fake-token'])
        ),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);
    $guzzleCommunication = new GuzzleCommunication($client);
    $response = $guzzleCommunication->requestApi("test", ['instance' => 'demo']);

    expect($response)->toBeArray()
        ->and($response)->toHaveKeys(['info', 'body'])
        ->and($response['info'])->toMatchArray([
            'http_code' => 200,
            'contentType' => 'application/json',
        ])
        ->and($response['body'])->toMatchArray(['authToken' => 'fake-token']);
});

it('can create a Zahls instance and newest api version', function () {
    $versions = Communicator::VERSIONS;
    $latestVersion = (string) end($versions);

    $zahls = new Zahls('demo', 'demo');

    expect($zahls)->toBeInstanceOf(Zahls::class)
        ->and($zahls->getVersion())->toBe($latestVersion);
});

it('can create a Zahls instance with GuzzleCommunication as handler', function () {
    new Zahls(
        'demo',
        'demo',
        Communicator::GUZZLE_COMMUNICATION_HANDLER,
        'zahls.ch');
})->throwsNoExceptions();
