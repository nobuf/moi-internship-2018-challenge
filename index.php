<?php

use Acme\Characters\Numeric;
use Acme\Hnb;
use Acme\HnbResponse;
use Acme\Solvers\SlightlyCarefulSolver;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

require_once 'vendor/autoload.php';

const END_POINT = 'https://apiv2.twitcasting.tv/internships/2018';
const END_POINT_GAME_START = END_POINT . '/games';
const END_POINT_GAME = END_POINT . '/games/%s';

if (empty($_SERVER['MOI_INTERN2018_TOKEN'])) {
    die('env MOI_INTERN2018_TOKEN must not be empty.' . PHP_EOL);
}

$level = $_SERVER['MOI_INTERN2018_LEVEL'] ?? 3;
$defaultHeaders = [
    'Authorization' => 'Bearer ' . $_SERVER['MOI_INTERN2018_TOKEN'],
];

try {
    $hnb = new Hnb();
    $hnb->setCharacters(Numeric::asArray())
        ->setLevel($level);

    // prepopulate stuff before starting a game
    $solver = new SlightlyCarefulSolver($hnb);

    $client = new Client();

    $response = $client->request('GET', END_POINT_GAME_START, [
        'query' => ['level' => $level],
        'headers' => $defaultHeaders,
    ]);
    $gameId = json_decode($response->getBody(), true)['id'];
    assert(!empty($gameId));

    echo 'Game ID: ', $gameId, PHP_EOL;

    $attempt = function (array $answer) use ($gameId, $level, $client, $defaultHeaders) {
        $response = $client->request('POST', sprintf(END_POINT_GAME, $gameId), [
            'headers' => $defaultHeaders,
            'json' => [
                'answer' => implode('', $answer),
            ]
        ]);
    
        $responseData = json_decode($response->getBody(), true);
    
        return [new HnbResponse($answer, $responseData['hit'], $responseData['blow']), $responseData];
    };
    
    $maxAttempts = 10;

    for ($i = 1; $i <= $maxAttempts; $i++) {
        $answer = $solver->getNextAnswer();
        list($response, $rawResponseData) = $attempt($answer);
    
        echo 'Hit: ', $response->getHit(),
            ', Blow: ', $response->getBlow(),
            ', Answer: ', implode(' ', $answer), PHP_EOL;
    
        if ($hnb->isSolved($response)) {
            echo sprintf('Solved at %d!', $i), PHP_EOL;
            break;
        }
    
        $solver->addHint($response);
    }
    
    var_dump($rawResponseData);    
} catch (ClientException $e) {
    echo $e->getMessage(), PHP_EOL;
    echo 'Timeout or, too soon to start a new game!', PHP_EOL;
}
