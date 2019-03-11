<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Session;
use App\Tax;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Setting;
use File;


class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $setting = Setting::whereId(1)->first();
        $taxes = Tax::all();

        if($setting) {
            $tax_rate = $setting->invoice_tax_rate;
        }else{
            $tax_rate = 0;
        }

        if(!$setting){
            $setting = new Setting;
        }

        /*$path = base_path().'/resources/lang';
        $directories = array_map('basename', File::directories($path));*/

        return view('settings.index')
            ->with('setting',$setting)
            ->with('taxes', $taxes)
            ->with('tax_rate', $tax_rate);
            // ->with('directories', $directories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postIndex(Request $request)
    {   
        $this->validate($request, [
            'site_name' => 'required|max:255',
            'email' => 'required|email',
            'address' => 'required|max:255',
            'phone' => 'required',
        ]);

        if($request->get('invoice_tax') == 1){
            $this->validate(
                        $request, 
                        ['invoice_tax_id' => 'required'], 
                        ['invoice_tax_id.required' => 'When you enable Order Tax, you must select Order Tax rate']
                );
        }

        $setting = Setting::findOrFail(1);
        $setting->site_name = $request->get('site_name');
        $setting->slogan = $request->get('slogan');
        $setting->address = $request->get('address');
        $setting->email = $request->get('email');
        $setting->phone = $request->get('phone');
        $setting->owner_name = $request->get('owner_name');
        $setting->currency_code = $request->get('currency_code');
        $setting->alert_quantity = $request->get('alert_quantity');
        $setting->product_tax = $request->get('product_tax');
        $setting->invoice_tax = $request->get('invoice_tax') ? 1 : 0;
        $setting->invoice_tax_rate = ($request->get('invoice_tax_id')) ? Tax::whereId($request->get('invoice_tax_id'))->first()->rate : 0;
        $setting->invoice_tax_type = ($request->get('invoice_tax_id')) ? Tax::whereId($request->get('invoice_tax_id'))->first()->type : 2;

        $setting->theme = $request->get('theme');
        $setting->enable_purchaser = $request->get('enable_purchaser');
        $setting->enable_customer = $request->get('enable_customer');
        $setting->vat_no = $request->get('vat_no');
        $setting->pos_invoice_footer_text = $request->get('pos_invoice_footer_text');
        $setting->dashboard = $request->get('dashboard_style');

        if($request->hasFile('image')){
            $file = $request->file('image');
            $imageSize = getimagesize($file);
            $width = $imageSize[0];
            $height = $imageSize[1];
            if($width > 190 || $height > 34){
                $warning = "Invalid Image Size";
                return redirect()->back()->withWarning($warning);
            }
            $file_extension = $file->getClientOriginalExtension();
            $random_string = str_random(12);
            $file_name = $random_string.'.'.$file_extension;
            $destination_path = public_path().'/uploads/site/';
            $request->file('image')->move($destination_path, $file_name);
            
            $setting->site_logo = $file_name;
        }

        $message = trans('core.changes_saved');
        $setting->save();

        return redirect()->back()->withSuccess($message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchLocale (Request $request, $locale)
    {
        session(['APP_LOCALE' => $locale]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBackup () {
        $host = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');
        
        $mysqli = new \mysqli($host, $username, $password, $database); 
        $mysqli->select_db($database); 
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables = $mysqli->query('SHOW TABLES');

        while($row = $queryTables->fetch_row()) { 
            $target_tables[] = $row[0]; 
        }

        foreach ($target_tables as $table) {
            $result         =   $mysqli->query('SELECT * FROM '.$table);  
            $fields_amount  =   $result->field_count;  
            $rows_num=$mysqli->affected_rows;     
            $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
                while ($row = $result->fetch_row()) { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 ) {
                        $content .= "\nINSERT INTO ".$table." VALUES";
                    }

                    $content .= "\n(";

                    for($j=0; $j<$fields_amount; $j++) {

                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j])); 
                        
                        if (isset($row[$j])) {
                            $content .= '"'.$row[$j].'"' ; 
                        } else {   
                            $content .= '""';
                        }     
                        
                        if ($j<($fields_amount-1)) {
                            $content.= ',';
                        }      
                    }
                    $content .=")";

                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {   
                        $content .= ";";
                    } else {
                        $content .= ",";
                    }

                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }
        $backup_name = $database."_".date('H-i-s')."_".date('d-m-Y')."_".str_random(5).".sql";
        header('Content-Type: application/octet-stream');   
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
        echo $content; exit;
    }

    public function verifyPurchase () {
        return view('auth/verify-purchase');
    }

    public function postVerifyPurchase (Request $request) {
        Storage::disk('storage')->put('purchase_code', trim($request->get('code')));
        return redirect()->to('install');
    }
}
