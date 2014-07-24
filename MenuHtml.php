<?php

/** Created by griga at 23.06.2014 | 13:36.
 *
 */
class MenuHtml
{

    public static function renderItems($items)
    {
        foreach ($items as $item) {
            echo self::menuItem($item);
        }
    }


    public static function menuItem($item)
    {
        $isParent = self::isParent($item);
        $out = CHtml::openTag('li', [
            'class' => (self::isActive($item) ? 'active' : '') . ($isParent ? ' dropdown' : ''),
        ]);
        if ($isParent) {
            $out .= CHtml::tag('a', [
                'href' => app()->createUrl($item['url']),
                'class' => (self::isActive($item) ? 'active' : ''),
            ], $item['label']);
            if (isset($item['caret']))
                $out .= $item['caret'];
            $out .= '<ul class="nav sub-menu">';
            foreach ($item['items'] as $child) {
                $out .= self::menuItem($child);
            }
            $out .= '</ul>';
        } else {
            $out .= CHtml::tag('a', ['href' => app()->createUrl($item['url'])], $item['label']);
        }

        $out .= CHtml::closeTag('li');
        return $out;
    }

    private static function isParent($item)
    {
        return (isset($item['items']) && is_array($item['items']) && count($item['items']));
    }

    private static function isActive($item)
    {
        $active = isset($item['active']) ? $item['active'] : false;
        if ('/' . r()->pathInfo == app()->createUrl($item['url'])) {
            $active = true;
        } elseif (self::isParent($item)) {
            foreach ($item['items'] as $child) {
                if (self::isActive($child)) {
                    $active = true;
                }
            }
        }
        return $active;
    }

    public static function catalogItems($withProducts = false)
    {
        $catalogItems = ProductCategory::model()->getDataForRecursiveRender($withProducts);
        $transformToItems = function ($catalogItems) use (&$transformToItems) {
            $items = [];
            foreach ($catalogItems as $catalogItem) {
                $items[] = [
                    'url' =>  ($catalogItem['alias'] ? '/' . $catalogItem['alias'] . '/' : '') . $catalogItem['url_id'],
                    'label' => $catalogItem['name'],
                    'items'=> $catalogItem['children'] ? $transformToItems($catalogItem['children']) : []
                ];
            }
            return $items;
        };
        return $transformToItems($catalogItems);
    }
} 