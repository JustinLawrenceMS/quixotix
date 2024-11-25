<?php

namespace Quixotix\Controllers;

use Quixotify\Controller;
use Quixotify\Generator as Quix;

class FakedDataController
{
    private $faker;
    private $quixotify;
    private $title;
    private $paragraph;
    private $floor;
    private $ceiling;
    private $language;
    private $controller;

    public function __construct(int $floor, int $ceiling, string $language, Quix $quixotify, Controller $controller)
    {
        $this->quixotify = $quixotify;
        $this->controller = $controller;
        $this->floor = $floor;
        $this->ceiling = $ceiling;
        $this->language = $language;
        $this->setTitle();
        $this->setParagraph();
    }

    public function setTitle(): void
    {
        $this->title = $this->quixotify->generate('characters', wp_rand(40, 50));
    }

    public function setParagraph(): void
    {
        $this->paragraph = $this->quixotify->generate('characters', 2000);
    }

    public function generateBlogPost(): array
    {
        $title = $this->title;

        $body = '';
        $paraNum = wp_rand($this->floor, $this->ceiling);
        for ($i = 0; $i < $paraNum; $i++) {
            $this->setParagraph();
            $body .= "<p>" . $this->paragraph . "</p>";
        }

        return [
            'title' => $title,
            'content' => "$body"
        ];
    }
}