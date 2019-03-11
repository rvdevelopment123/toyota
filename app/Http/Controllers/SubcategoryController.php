<?php
namespace App\Http\Controllers;

use App\Category;
use App\Subcategory;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubcategoryFormRequest;

class SubcategoryController extends Controller
{

    private $searchParams = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $subcategories = Subcategory::orderBy('name', 'asc');

        if($request->get('name')) {
            $subcategories->where(function($q) use($request) {
                $q->where('name', 'LIKE', '%' . $request->get('name') . '%');
            });
        }

        return view('subcategories.index')->withSubcategories($subcategories->paginate(20));
    }


    /**
     * post method of index
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postIndex(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('SubcategoryController@getIndex', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewSubcategory()
    {
        $subcategory = new Subcategory;
        $category = Category::pluck('category_name', 'id');
        return view('subcategories.form')
                ->withSubcategory($subcategory)
                ->withCategory($category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postSubcategory(SubcategoryFormRequest $request, Subcategory $subcategory)
    {
        $subcategory->name = ucfirst($request->get('name'));
        $subcategory->category_id = $request->get('category_id');
        $subcategory->save();

        $message = trans('core.changes_saved');
        return redirect()->route('subcategory.index')->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditSubcategory(Subcategory $subcategory)
    {
        $category = Category::pluck('category_name', 'id');
        return view('subcategories.form')
                    ->withSubcategory($subcategory)
                    ->withCategory($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSubcategory(Subcategory $subcategory)
    {
       if(count($subcategory->products) ===  0){
            $subcategory->delete();
            $message = trans('core.subcategory_deleted');
            return redirect()->back()->withSuccess($message);
        }
        $message = trans('core.subcategory_has_products');
        return redirect()->back()->withMessage($message);
    }

    /**
     * View the list of products of a subcategory.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProductList(Subcategory $subcategory){
        $products = $subcategory->products();
        return view('subcategories.products')->withProducts($products->paginate(20));
    }
}
