<?php

namespace App\Classes;

use Illuminate\Mail\Markdown;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use Illuminate\Support\HtmlString;

class Markdown2 extends Markdown
{
    
   public static function parse($text)
    {
        $config = [
            'html' => ['allow_unsafe_links' => false],
            'external_link' => [
                'internal_hosts' => '',
                'open_in_new_window' => true,
                'html_class' => 'external-link',
                'nofollow' => '',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());
        $environment->addExtension(new ExternalLinkExtension());

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($text);

        return new HtmlString($html);
    }
}