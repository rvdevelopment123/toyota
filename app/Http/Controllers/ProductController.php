<?php
namespace App\Http\Controllers;

use App\Tax;
use App\Client;
use App\Product;
use App\Category;
use App\Subcategory;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{   
    private $searchParams = ['name', 'code'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getIndex(Request $request)
    {
        $products = Product::orderBy('name', 'asc');

        if($request->get('name')) {
            $products->where(function($q) use($request) {
                $q->where('name', 'LIKE', '%' . $request->get('name') . '%');
            });
        }

        if($request->get('code')) {
            $products->where('code','LIKE', '%' . $request->get('code') . '%');
        }

        return view('products.index')->withProducts($products->paginate(20));
    }


    public function postIndex(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('ProductController@getIndex', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewProduct()
    {
        $current_locale = app()->getLocale();
        \App::setLocale('ar');
        $secondary_lang = \Lang::get('core');
        \App::setLocale($current_locale);

        $product = new Product;
        $categories = Category::pluck('category_name','id');
        $subcategories = Subcategory::pluck('name','id');
        $taxes = Tax::pluck('name', 'id');
        return view('products.form')
                    ->withProduct($product)
                    ->withCategories($categories)
                    ->withSubcategories($subcategories)
                    ->withTaxes($taxes)
                    ->with('secondary_lang',$secondary_lang);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postProduct(ProductRequest $request, Product $product)
    {
        $checkProductByCode = Product::where('code', $request->get('code'))->get();
        $checkProductByName = Product::where('name', $request->get('name'))->get();

        if($product->id == null){
            if($checkProductByName->count() != 0 ){
                $errors = $request->get('name'). " Already Exist!" ;
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }

            if($checkProductByCode->count() != 0){
                $errors = "Duplicate Product Code (" . $request->get('code'). ") !" ;
                return redirect()->back()->withInput($request->input())->withErrors($errors);
            }
        }
        

        $product->category_id = $request->get('category_id');
        $product->subcategory_id = $request->get('subcategory_id');
        $product->name = $request->get('name');
        $product->code = $request->get('code');
        
        $product->cost_price = $request->get('cost_price');
        $product->mrp = $request->get('mrp');
        $product->minimum_retail_price = $request->get('minimum_retail_price');
        $product->tax_id = $request->get('tax_id');
        $product->unit = $request->get('unit');
        $product->details = $request->get('details');
        $product->status = $request->get('status') ? $request->get('status') : 0;

        //opening stocks
        $current_stock = ($product->id) ? $product->quantity : 0;
        $product->quantity = $current_stock + $request->get('opening_stock');
        $product->opening_stock = $request->get('opening_stock');

        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_extension = $file->getClientOriginalExtension();
            $random_name = str_random(12);
            $destination_path = public_path().'/uploads/products/';
            $filename = $random_name.'.'.$file_extension;
            $request->file('image')->move($destination_path,$filename);

            $product->image = $filename;
        }

        $message = trans('core.changes_saved');
        $product->save();

        return redirect()->route('product.index')->withSuccess($message);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditProduct(Product $product)
    {   
        $current_locale = app()->getLocale();
        \App::setLocale('ar');
        $secondary_lang = \Lang::get('core');
        \App::setLocale($current_locale);

        $categories = Category::pluck('category_name', 'id');
        $subcategories = Subcategory::pluck('name','id');
        $taxes = Tax::pluck('name', 'id');
        return view('products.form')
                ->withProduct($product)
                ->withSubcategories($subcategories)
                ->withCategories($categories)
                ->withTaxes($taxes)
                ->with('secondary_lang',$secondary_lang);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProductDetails(Product $product)
    {   
        return view('products.details')->withProduct($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct(Product $product)
    {
        if(count($product->sells) == 0 && count($product->purchases) == 0){
            $product->delete();
            $message = trans('core.deleted');
            return redirect()->back()->withMessage($message);
        }else{
            $message = trans('core.product_has_sells');
            return redirect()->back()->withMessage($message);
        }
    }

    /**
     * Update the price of a product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePrice(Request $request){
        $id = $request->get('product_id');
        $product = Product::find($id);
        $product->cost_price = $request->get('cost_price');
        $product->mrp = $request->get('mrp');
        $product->save();

        $message = trans('core.product_price_updated');
        return redirect()->route('product.index')->withSuccess($message);
    }


    /**
     * Return all the products that has errors
     *
     * @return \Illuminate\Http\Response
     */
    public function alertProduct()
    {
        $alertQuantity = settings('alert_quantity');
        $products = Product::orderBy('id', 'asc')->where('quantity', '<' , $alertQuantity)->get();
        return view('products.alert-products-list', compact('products'));
    }


    /**
     * Print all the products
     *
     * @return \Illuminate\Http\Response
     */
    public function printAllProduct(){
        $products = Product::all();
        return view('products.print', compact('products'));
    }

    /**
     * Print Barcode 
     *
     * @return \Illuminate\Http\Response
     */
    public function printBarcode(){
        $products = Product::orderBy('name', 'asc')->where('status', 1)->select('id','name','cost_price', 'mrp','minimum_retail_price','quantity', 'tax_id', 'code')->get();

        return view('products.print-barcode', compact('products'));
    }

    /**
     * Print Barcode 
     *
     * @return \Illuminate\Http\Response
     */
    public function printSingleBarcode(Product $product){
        return view('products.print-single-barcode', compact('product'));
    }
   
}
