<?php
namespace App\Services;

use Storage;
use Carbon\Carbon;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\Log;

class LicenseService
{

    private $itemId;
    private $apiUrl;
    protected $logger;
    private $personalToken;
    

    public function __construct () {
        $logger = new Logger('LicenseService');
        $logger->pushHandler(new StreamHandler(storage_path('LicenseService.log'), Logger::INFO));
        $this->logger = $logger;

        $this->itemId = '';
        $this->personalToken = config('app.licensor');
        $this->apiUrl = 'https://api.envato.com/v3/market/author/sale?code=';

    }

    public function verifyLicense () {

        // check license
        if($this->hasLicense()) {
            $verifiedLicense = $this->verifyLicenseCode();
            if ($verifiedLicense) {
                return true;
            }
        }

        $purchaseCode = Storage::disk('storage')->get('purchase_code');
        $purchaseCode = trim($purchaseCode);
        
        $bearer   = 'Bearer '.$this->personalToken;
        $header   = [];
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verificationUrl = $this->apiUrl.$purchaseCode;
        // echo $verificationUrl; exit;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $verificationUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->personalToken]); 
        curl_setopt($ch, CURLOPT_USERAGENT, 'Intelle Hub');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        // print_r($error); exit;
        curl_close($ch);

        $this->logger->addInfo(base64_encode('License verification: Server responded with ['.$response.'] at ['.Carbon::now().']'));

        $decodedResponse = json_decode($response);

        if (property_exists($decodedResponse, 'item')) {
            $licence = trim(config('app.license'));
            // $itemId = $decodedResponse->item->id;
            // Save license
            Storage::disk('storage')->put('lic', $licence);
            $this->logger->addInfo(base64_encode('License verification: Product is successfully licensed at ['.Carbon::now().']'));
        } else {
            $this->logger->addInfo(base64_encode('License verification: unlicensed/counterfeited software aborted with code 402 at ['.Carbon::now().']'));
            abort(402);
        }
    
    }

    public function verifyPurchase () {
        $purchase = Storage::disk('storage')->exists('purchase_code');
        return $purchase;
    }

    public function hasLicense ($verify = false) {
        $license = Storage::disk('storage')->exists('lic');
        if ($verify) {
            return $this->verifyLicenseCode();
        }
        return $license;
    }

    public function verifyLicenseCode () {
        if (Storage::disk('storage')->exists('lic')) {
            $licenseCodeRaw = Storage::disk('storage')->get('lic');
            $licenseCode = sha1(trim($licenseCodeRaw));
            $storedLicense = sha1(trim(config('app.license')));
            if ($licenseCode === $storedLicense) {
                return true;
            }
        }
        $this->logger->addInfo(base64_encode('An invalid license code ['.$licenseCodeRaw.'] was provided. ['.Carbon::now().']'));
        abort(402);
    }

}
