<?php

namespace App\Models;
class Post
{
    private User $author;
    private int $id;
    private string $title;
    private string $body;
    private string $imageUrl;

    public function __construct
    (
        User $author,
        int $id,
        string $title,
        string $body,
        string $imageUrl
    )
    {
        $this->author = $author;
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->imageUrl = $imageUrl;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }


    public function getBody(): string
    {
        return $this->body;
    }


    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

}
