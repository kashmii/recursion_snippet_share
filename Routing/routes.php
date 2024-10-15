<?php

require_once __DIR__ . '/../config.php';

use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Controllers\SnippetController;

return [
  '/' => function (): HTTPRenderer {
    return new HTMLRenderer('index');
  },
  '/list' => function (): HTTPRenderer {
    $controller = new SnippetController();
    $snippets = $controller->list();
    return new HTMLRenderer('list', ['snippets' => $snippets]);
  },
  '/submit' => function () {
    $controller = new SnippetController();
    $controller->submit();
    return new HTMLRenderer('index');
  },
  // show のルーティング
  SHOW_ROUTE_PATTERN => function ($token): HTTPRenderer {
    // $controller = new SnippetController();
    // $controller->show($token);
    return new HTMLRenderer('show');
  },

];
