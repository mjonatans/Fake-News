<?php declare(strict_types=1);

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Renderer
{
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../App/Views');
        $this->twig = new Environment($loader);
    }

    public function render(View $view): string
    {
        return $this->twig->render($view->getTemplate() . '.html.twig', $view->getData());
    }
}