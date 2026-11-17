<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class BeritaHtml
{
    public static function sanitize(string $html): string
    {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML('<?xml encoding="UTF-8"><body>'.$html.'</body>', LIBXML_NONET);
            $body = $document->getElementsByTagName('body')->item(0);
            if (! $body) {
                return '';
            }
            self::clean($body);

            return implode('', array_map(fn (DOMNode $node): string => $document->saveHTML($node), iterator_to_array($body->childNodes)));
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    private static function clean(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }
            $tag = strtolower($node->tagName);
            if (in_array($tag, ['script', 'style', 'iframe', 'object', 'svg', 'math', 'template'], true)) {
                $parent->removeChild($node);

                continue;
            }
            self::clean($node);
            if (! in_array($tag, ['p', 'div', 'br', 'h2', 'h3', 'strong', 'b', 'em', 'i', 'u', 'ul', 'ol', 'li', 'blockquote', 'a', 'img', 'span'], true)) {
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);

                continue;
            }
            foreach (iterator_to_array($node->attributes) as $attribute) {
                $name = $attribute->name;
                $value = trim($attribute->value);
                $allowed = ($tag === 'img' && $name === 'alt')
                    || ($tag === 'a' && $name === 'href' && preg_match('~^(https?://|mailto:|/(?!/)|#)~i', $value))
                    || ($tag === 'img' && $name === 'src' && preg_match('~^(https?://|/(?!/)|data:image/(png|jpeg|webp|gif);base64,)~i', $value));
                if (! $allowed) {
                    $node->removeAttribute($name);
                }
            }
        }
    }
}
