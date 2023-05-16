<?php

namespace App\Controllers;

use App\ApiClient;
use App\Core\View;

class PostController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }
    public function singlePost(array $vars) : View
    {
        $article = $this->client->getPost((int)implode('', $vars));
        var_dump($article);die;
        if (!$article) {
            return new View('notFound', []);
        }
        $comments = $this->client->getCommentsById($article->getId());
        return new View('singleArticle', ['article' => $article, 'comments' => $comments]);
    }

    public function singleUser(array $vars): View
    {
        $user = $this->client->getUser((int)implode('', $vars));
        var_dump($user);die;
        if (!$user) {
            return new View('notFound', []);
        }
        $articles = $this->client->getPostsByUser($user->getId());
        return new View('singleUser', ['user' => $user, 'articles' => $articles]);
    }

    public function allPosts() : View
    {
        $data = $this->client->getPosts();
        return new View('allPosts', ['data' => $data]);
    }

    public function allUsers() : View
    {
        $data = $this->client->getUsers();
        return new View('allUsers', ['data' => $data]);
    }
}