<?php

namespace notify_events\helpers;

/**
 * Class Html
 * @package notify_events\helpers
 */
class Html
{
    public static function tag($tag, $content = null, $attrs = [])
    {
        foreach ($attrs as $key => $value) {
            $attrs[$key] = esc_attr($key) . '="' . esc_attr($value) . '"';
        }

        $attrs = implode(' ', $attrs);

        if ($content) {
            return sprintf('<%1$s %2$s>%3$s</%1$s>', esc_attr($tag), $attrs, $content);
        } else {
            return sprintf('<%1$s %2$s />', esc_attr($tag), $attrs);
        }
    }

    /**
     * @param string $text
     * @param array|string $url
     * @param array $attrs
     * @return string
     */
    public static function a($text, $url, $attrs = [])
    {
        if (is_array($url)) {
            $url = Url::to($url);
        }

        $attrs['href'] = $url;

        return static::tag('a', $text, $attrs);
    }
}