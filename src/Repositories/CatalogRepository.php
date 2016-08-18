<?php

namespace Liteweb\Catalog\Repositories;

use Liteweb\Catalog\Models\Catalog;
use Liteweb\Catalog\Models\DefaultParam;
use DB;

class CatalogRepository 
{

    public function all()
    {
        return Catalog::all();
    }

    public function wholeTree()
    {
        $catalog = Catalog::all();
    }

    public function getCatalogFields($id)
    {
        return Catalog::find($id);
    }

    public function addCategory($data)
    {
        $category = new Catalog;
        $category->parent = $data['parent'];
        $category->name = $data['name'];
        $category->save();
        return $category;
    }

    public function getDealtypes($id)
    {
        $category = new Catalog;
        $category->parent = $data['parent'];
        $category->name = $data['name'];
        $category->save();
        return $category;
    }

    public function editCatalog($id, $column, $value)
    {
        $catalog = Catalog::find($id);
        $catalog->$column = $value;
        $catalog->save();
    }

    public function deleteCatalog($id)
    {
        Catalog::destroy($id);
        Catalog::where('parent', $id)->delete();
    }

    function getCatalogTree($separator = false){
        $cat = Catalog::orderBy('name', 'asc')->get();

        foreach ($cat as $c)
        {
            $all[$c->parent][] = array('id' => $c->id, 'name'=> $c->name, 'parent' => $c->parent);
        }
        $json = $this->buildCatalog($all, 0, $separator);

        return $json;
    }

    function buildCatalog($catalog, $parent, $separator = false, $sep = ''){
        $out = array();
        if (!isset($catalog[$parent]))
        {
            return $out;
        }
        foreach ($catalog[$parent] as $row)
        {
            $chidls = $this->buildCatalog($catalog, $row['id'], $separator, ($separator == true) ?  '---' . $sep : '');
            if ($chidls)
            {
                $row['children'] = $chidls;
            }
            $row['name'] = ($sep != '') ? $sep . ' ' . $row['name'] : $row['name'];
            $out[] = $row;
        }
        return $out;
    }

}