<?php

namespace Quixotify;

use Exception;

class Generator
{
    private Controller $client;

    public function __construct(Controller $client)
    {
        $this->client = $client;
    }

    public function generate($type, $amount): string
    {
        try {
            return $this->client->generateText($type, $amount);
        } catch (Exception $e) {
            return file_put_contents('php://stderr', $e->getMessage());
        }
    }
}
