<?php
/** Created by griga at 14.07.2014 | 17:28.
 * 
 */

class DataSrv {

    public static function getBrands(){
        $brands = db()->createCommand()->select('id,name,alias')->from('{{product_manufacturer}}')->queryAll();
        $products = db()->createCommand()
            ->select('p.id, p.name, p.alias, p.price, p.manufacturer_id, p.featured, p.short_content, u.filename as image')
            ->from('{{product}} p')
            ->order('p.sort')
            ->join('{{upload}} u', 'u.entity_id = p.id')
            ->where('u.entity = "Product"')->queryAll();
        foreach ($brands as &$brand) {
            if (!isset($brand['products']))
                $brand['products'] = [];
            foreach ($products as $productKey => $product) {
                if ($product['manufacturer_id'] == $brand['id']) {
                    $brand['products'][] = $product;
                    unset($products[$productKey]);
                }
            }
        }
        return $brands;
    }

    public static function getBrand($alias){
        $brand = db()->createCommand()->select('id,name,alias')->from('{{product_manufacturer}}')->where(
            'alias = :alias', [':alias' => $alias]
        )->queryRow();
        $brand['products'] =
            db()->createCommand()
                ->select('p.id, p.name, p.alias, p.price, p.manufacturer_id, p.featured, p.short_content, u.filename as image')
                ->from('{{product}} p')
                ->join('{{upload}} u', 'u.entity_id = p.id')
                ->where('u.entity = "Product"')
                ->andWhere('p.manufacturer_id=:mid', [':mid' => $brand['id']])
                ->order('p.sort')
                ->queryAll();

        return $brand;
    }

    public static function getProduct($alias){
        $product = db()->createCommand()
            ->select('p.id, p.name, p.alias, p.price, p.manufacturer_id, p.featured, p.short_content, p.content, u.filename as image')
            ->from('{{product}} p')
            ->join('{{upload}} u', 'u.entity_id = p.id')
            ->where('u.entity = "Product"')
            ->andWhere('p.alias=:alias', [':alias' => $alias])->queryRow();
        $product['brand']= db()->createCommand()->select('id,name,alias')->from('{{product_manufacturer}}')->where(
            'id='.$product['manufacturer_id']
        )->queryRow();

        return $product;
    }

    public static function getPage($alias)
    {
        return db()->createCommand()
            ->select('c.name, c.alias, c.short_content, c.content')
            ->from('{{content}} c')
            ->andWhere('c.type='.Content::TYPE_PAGE)
            ->andWhere('c.alias=:alias', [':alias' => $alias])->queryRow();


    }
} 