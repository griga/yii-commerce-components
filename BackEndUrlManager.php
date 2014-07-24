<?php
/** Created by griga at 29.10.13 | 15:26.
 * 
 */

class BackEndUrlManager extends CUrlManager{

    public function parseUrl($request)
    {
        $route = parent::parseUrl($request);
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $route))));
    }

} 