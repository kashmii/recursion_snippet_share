<?php

// use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Controllers\SnippetController;



return [
    '/' => function (): HTTPRenderer {
        return new HTMLRenderer( 'index' );
    },
    '/show' => function (): HTTPRenderer {
        return new HTMLRenderer( 'show' );
    },
    '/list' => function (): HTTPRenderer {
        $controller = new SnippetController();
        $controller->list();
        return new HTMLRenderer('list');
    },
    '/submit' => function () {
        $controller = new SnippetController();
        $controller->submit();
        return new HTMLRenderer('index');
    },
    // show のルーティング
    '/([a-zA-Z0-9]{13,})' => function ($token): HTTPRenderer {
        // $controller = new SnippetController();
        // $controller->show($token);
        return new HTMLRenderer('show');
    },

];