<?php declare(strict_types=1);

namespace App;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class ApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://jsonplaceholder.typicode.com/',
            'verify' => false
        ]);
    }

    public function getPost(int $id): ?Post
    {
        try {
            $cacheKey = 'article_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('posts/' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            return $this->createPost(json_decode($responseContent));
        } catch (GuzzleException $e) {
            return null;
        }
    }

    public function getUser(int $id): ?User
    {
        try {
            $cacheKey = 'user_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('users/' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            return $this->createUser(json_decode($responseContent));
        } catch (GuzzleException $e) {
            return null;
        }
    }

    public function getPosts() : array
    {
        try {
            if (!Cache::has('articles')) {
                $response = $this->client->get('posts');
                $responseContent = $response->getBody()->getContents();
                Cache::save('articles', $responseContent);
            } else {
                $responseContent = Cache::get('articles');
            }

            $postCollection = [];
            foreach (json_decode($responseContent) as $post) {
                $postCollection[] = $this->createPost($post);
            }
            return $postCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getUsers() : array
    {
        try {
            if (!Cache::has('users')) {
                $response = $this->client->get('users');
                $responseContent = $response->getBody()->getContents();
                Cache::save('users', $responseContent);
            } else {
                $responseContent = Cache::get('users');
            }

            $userCollection = [];
            foreach (json_decode($responseContent) as $user) {
                $userCollection[] = $this->createUser($user);

            }
            return $userCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getCommentsById(int $id): array
    {
        try {
            $cacheKey = 'comments_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('comments?postId=' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            $commentCollection = [];
            foreach (json_decode($responseContent) as $comment) {
                $commentCollection[] = $this->createComment($comment);
            }
            return $commentCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function getPostsByUser(int $id): array
    {
        try {
            $cacheKey = 'articles_user_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('posts?userId=' . $id);
                $responseContent = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContent);
            } else {
                $responseContent = Cache::get($cacheKey);
            }
            $articleCollection = [];
            foreach (json_decode($responseContent) as $post) {

                $articleCollection[] = $this->createPost($post);
            }
            return $articleCollection;
        } catch (GuzzleException $e) {
            return [];
        }
    }


    private function createPost(stdClass $post) : Post
    {
        return new Post(
            $this->getUser($post->userId),
            $post->id,
            $post->title,
            $post->body,
            'https://placehold.co/600x400/2E3273/FFF?text=Sample+Text'
        );
    }

    private function createUser(stdClass $user) : User
    {
        return new User(
            $user->id,
            $user->name,
            $user->username,
            $user->email,
            $user->phone,
            $user->website
        );
    }

    private function createComment(stdClass $comment) : Comment
    {
        return new Comment(
            $comment->postId,
            $comment->id,
            $comment->name,
            $comment->email,
            $comment->body
        );
    }

}
