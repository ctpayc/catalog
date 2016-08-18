<?php 

namespace Liteweb\Catalog\Http\Controllers;

use Illuminate\Http\Request;

use Liteweb\Catalog\Repositories\CatalogRepository;

use Illuminate\Routing\Controller as BaseController;

use DB;
use Storage;

class CatalogController extends BaseController
{

    public function __construct(CatalogRepository $catalog)
    {
        $this->catalog = $catalog;
    }

    public function index()
    {
        $data['categories'] = $this->catalog->getCatalogTree();

        return view('liteweb-catalog::tree', $data);
    }

    public function test()
    {
        $params = Storage::get('default_params/default_params.json');
        $default_params = json_decode($params, JSON_UNESCAPED_SLASHES);
        foreach ($default_params as $key => $value) {
            echo $key . '<br />';
        }
        dd($default_params);
        return view('liteweb-catalog::select', $data);
    }

    public function getCategoriesSelect()
    {
        $data['categories'] = $this->catalog->getCatalogTree(true);
        return response()->json(view('liteweb-catalog::select', $data)->render());
        // response()->json(['status' => 400, 'messages' => 'deleted successfully']);
        // return view('liteweb-catalog::select', $data);
    }

    public function getDealtypes()
    {
        $data['deal_types'] = $this->catalog->getDealtypes();
        return response()->json(view('liteweb-catalog::select', $data)->render());
        // response()->json(['status' => 400, 'messages' => 'deleted successfully']);
        // return view('liteweb-catalog::select', $data);
    }

    public function fix()
    {
        DB::select(DB::raw("SELECT setval(pg_get_serial_sequence('catalogs', 'id'), max(id)) FROM catalogs"));
        echo(json_encode(['status' => 'ok']));
    }

    public function category($id = null)
    {
        $data['category'] = $this->catalog->getCatalogFields($id);
        return response()->json(['category' => $data['category']]);
        // return view('liteweb-catalog::category', $data);
    }

    public function addcategory(Request $request)
    {
        $category = $this->catalog->addCategory($request->all());
        return response()->json(['status' => 200, 'category' => $category, 'messages' => 'category added successfully']);
    }

    public function delete(Request $request, $catalog, $type)
    {
        switch ($type) {
            case null:
            case 'catalog':
                $this->catalog->deleteCatalog($catalog);
                break;
            case 'deal_type':
            case 'param':
                $fields = $request->input('data');
                $this->catalog->editCatalog($catalog, 'fields', $fields);
                break;
            default:
                break;
        }
        return response()->json(['status' => 200, 'messages' => 'deleted successfully']);
    }

    public function edit(Request $request, $catalog, $type)
    {
        switch ($type) {
            case null:
            case 'catalog':
                $name = $request->input('data');
                $this->catalog->editCatalog($catalog, 'name', $name);
                break;
            case 'deal_type':
            case 'param':
                $fields = $request->input('data');
                $this->catalog->editCatalog($catalog, 'fields', $fields);
                break;
            default:
                break;
        }
        return response()->json(['status' => 200, 'messages' => 'edited successfully']);
    }


}