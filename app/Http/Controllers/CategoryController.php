<?php
namespace App\Http\Controllers;

use Input;
use App\Category;
use App\Subcategory;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private $searchParams = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $categories = Category::orderBy('category_name', 'asc');

        if($request->get('name')) {
            $categories->where(function($q) use($request) {
                $q->where('category_name', 'LIKE', '%' . $request->get('name') . '%');
            });
        }

        return view('categories.index')->withCategories($categories->paginate(20));
    }

    /**
     * post method of index.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postIndex(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('CategoryController@getIndex', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewCategory()
    {
        $category = new Category;
        return view('categories.form')->withCategory($category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCategory(CategoryRequest $request, Category $category)
    {
        $category->category_name = $request->get('name');
        $category->save();

        $message = trans('core.changes_saved');
        return redirect()->route('category.index')->withSuccess($message);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditCategory(Category $category)
    {
        return view('categories.form')->withCategory($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCategory(Category $category)
    {   
        if(count($category->subcategories) ==  0 && count($category->product) == 0){
            $category->delete();
            $message = trans('core.deleted');
            return redirect()->back()->withMessage($message);
        }else{
            $message = trans('core.category_has_subcategories');
            return redirect()->back()->withMessage($message);
        }
    }


     /**
     * Load Subcategory of a category
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxRequest(Request $request)
    {
        $category_id = $request->get('categoryID');
        $subcategory = Subcategory::where('category_id', $category_id)->get();
        return view('categories.subcategory', compact('subcategory'));
    }
}
