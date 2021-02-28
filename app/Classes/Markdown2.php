<?php

namespace App\Classes;

use Illuminate\Mail\Markdown;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use Illuminate\Support\HtmlString;

class Markdown2 extends Markdown
{
    
   public static function parse($text)
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new TableExtension);
        $environment->addExtension(new ExternalLinkExtension());

        // https://commonmark.thephpleague.com/1.5/extensions/external-links/
        $config = [
            'allow_unsafe_links' => false,
            'external_link' => [
                'internal_hosts' => '',
                'open_in_new_window' => true,
                'html_class' => 'external-link',
                'nofollow' => '',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
        ];
        
        $converter = new CommonMarkConverter($config, $environment);

        return new HtmlString($converter->convertToHtml($text));
    }
}