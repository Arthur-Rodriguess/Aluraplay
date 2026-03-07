<?php

namespace Alura\Mvc\Helper;

trait HtmlRendererTrait
{
    private const TEMPLATE_PATH = __DIR__ . "/../../views/";

    private function renderTemplate(string $templateName, array $context = []): string
    {
        extract($context);
        ob_start();
        require_once self::TEMPLATE_PATH . $templateName . ".php";
        $html = ob_get_clean();
        return $html;
    }
}