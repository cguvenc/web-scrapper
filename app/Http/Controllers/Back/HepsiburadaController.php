<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\FetchProductJob;
use Illuminate\Support\Sleep;




class HepsiburadaController extends Controller
{

    public $client;

    public $availableUserAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (Linux; Android 10; ELE-L29; HMSCore 5.1.0.326; GMSCore 21.45.16) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 HuaweiBrowser/11.0.4.302 Mobile Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36 RuxitSynthetic/1.0 v631575215780390069 t3891685320802505868 ath1fb31b7a altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36 RuxitSynthetic/1.0 v5294783468179500671 t4684696140914125110 ath93eb305d altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36 RuxitSynthetic/1.0 v8278484541268018846 t4619802339571665632 athe94ac249 altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36 RuxitSynthetic/1.0 v14166208213 t4787681247261519342 athfa3c3975 altpub cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36 RuxitSynthetic/1.0 v7060074076478325169 t5654132216862743399 ath259cea6f altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36 RuxitSynthetic/1.0 v7986046742216733005 t2143354459458917537 ath5ee645e0 altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36 RuxitSynthetic/1.0 v4434828023 t3013442436289280341 athfa3c3975 altpub cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36 RuxitSynthetic/1.0 v6471286578605830700 t6583828985718256818 athe94ac249 altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36 RuxitSynthetic/1.0 v3648109001892537985 t7527522693257895152 ath5ee645e0 altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36 RuxitSynthetic/1.0 v6879747213363234207 t2513504243886480416 ath5ee645e0 altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36 RuxitSynthetic/1.0 v14166276391 t4787681247261519342 athfa3c3975 altpub cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36 RuxitSynthetic/1.0 v4398734595343620084 t894544887429309181 ath5ee645e0 altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.164 Safari/537.36 RuxitSynthetic/1.0 v2183999373738286605 t1171836933865741988 ath2653ab72 altpriv cvcv=2 smf=0',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/531.0 (KHTML, like Gecko) Version/15.0 EdgiOS/97.01121.46 Mobile/15E148 Safari/531.0',
        'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 5.01; Trident/5.0)',
        'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20100917 Firefox/36.0',
        'Opera/9.10 (Windows CE; nl-NL) Presto/2.9.167 Version/10.00',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_1) AppleWebKit/5361 (KHTML, like Gecko) Chrome/39.0.825.0 Mobile Safari/5361',
        'Opera/8.57 (Windows NT 6.2; nl-NL) Presto/2.12.347 Version/10.00',
        'Opera/9.72 (X11; Linux i686; en-US) Presto/2.12.311 Version/12.00',
        'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/80.0.4076.30 Safari/532.0 Edg/80.01065.8',
        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_7_4) AppleWebKit/5340 (KHTML, like Gecko) Chrome/39.0.853.0 Mobile Safari/5340',
        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_8) AppleWebKit/531.0 (KHTML, like Gecko) Chrome/96.0.4343.70 Safari/531.0 Edg/96.01117.43',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/84.0.4826.56 Safari/534.1 EdgA/84.01071.3',
        'Mozilla/5.0 (compatible; MSIE 5.0; Windows 98; Win 9x 4.90; Trident/5.1)',
        'Mozilla/5.0 (compatible; MSIE 8.0; Windows 95; Trident/5.0)',
        'Opera/8.86 (Windows NT 5.0; sl-SI) Presto/2.12.331 Version/12.00',
        'Mozilla/5.0 (Windows NT 6.0; nl-NL; rv:1.9.2.20) Gecko/20121124 Firefox/37.0',
        'Opera/9.66 (X11; Linux i686; sl-SI) Presto/2.8.334 Version/11.00'
    ];

    public $storeUrl;
    public $consumerKey;
    public $consumerSecret;

    public function __construct()
    {
        ini_set('max_execution_time', 24000);

        $randomUserAgent = $this->availableUserAgents[array_rand($this->availableUserAgents)];

        $this->client = new Client([
            'headers' => [
                'User-Agent' => $randomUserAgent,
            ],
            'timeout' => 200,
            'proxy' => [
                'http' => 'http://scraperapi:20d1957838cd61cf6a6c2a66212a3c48@proxy-server.scraperapi.com:8001',
                'https' => 'http://scraperapi:20d1957838cd61cf6a6c2a66212a3c48@proxy-server.scraperapi.com:8001',
            ],
            RequestOptions::VERIFY => false
        ]);
    }


    public function index($store_url, $consumerKey, $consumerSecret, $store, $product_count, $reviewMin, $reviewMax, $categorie_id)
    {

        $this->storeUrl = $store_url;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;

        $url = "https://www.hepsiburada.com/$store";

        $productCount = $product_count;
        $commentCount = rand($reviewMin, $reviewMax);
        $storeProductCount = 0;
        $pageCount = 1;
        $categorieId = $categorie_id;


        $response = $this->client->get($url);
        $content = $response->getBody()->getContents();
        $crawler = new Crawler($content);

        $crawler->filter('a.moria-ProductCard-gyqBb')->each(function ($node) use (&$storeProductCount, $productCount,$commentCount,$categorieId) {
            if ($storeProductCount < $productCount) {

                $link = $node->attr('href');
                Log::info('link: '.$link );

                if (strpos($link, 'https://') === 0) {
                   // $this->fetchProduct($link,$commentCount,$categorieId);
                   dispatch(new FetchProductJob(['store_url' => $this->storeUrl, 'consumer_key' => $this->consumerKey, 'consumer_secret' => $this->consumerSecret],$link,$commentCount,$categorieId));
                } else {
                  //  $this->fetchProduct('https://hepsiburada.com' . $link,$commentCount,$categorieId);
                  dispatch(new FetchProductJob(['store_url' => $this->storeUrl, 'consumer_key' => $this->consumerKey, 'consumer_secret' => $this->consumerSecret],'https://hepsiburada.com'.$link,$commentCount,$categorieId));
                }

                $storeProductCount++;
            }
        });


        for ($pageCount = 1; $storeProductCount < $productCount; $pageCount++) {

            $nextUrl = $url . '?sayfa=' . $pageCount;

            $response = $this->client->get($nextUrl);
            $content = $response->getBody()->getContents();
            $crawler = new Crawler($content);

            $products = $crawler->filter('a.moria-ProductCard-gyqBb');

            if ($products->count() === 0) {
                break;
            }

            $products->each(function ($node) use (&$storeProductCount, $productCount, $pageCount, $commentCount, $categorieId) {

                $link = $node->attr('href');
                Log::info('link: '.$link );

                if (strpos($link, 'https://') === 0) {
                    // $this->fetchProduct($link,$commentCount,$categorieId);
                    dispatch(new FetchProductJob(['store_url' => $this->storeUrl, 'consumer_key' => $this->consumerKey, 'consumer_secret' => $this->consumerSecret],$link,$commentCount,$categorieId));
                 } else {
                   //  $this->fetchProduct('https://hepsiburada.com' . $link,$commentCount,$categorieId);
                   dispatch(new FetchProductJob(['store_url' => $this->storeUrl, 'consumer_key' => $this->consumerKey, 'consumer_secret' => $this->consumerSecret],'https://hepsiburada.com'.$link,$commentCount,$categorieId));
                 }

                $storeProductCount++;

                if ($storeProductCount >= $productCount) {
                    return false;
                }
            });
        }
    }

    public function productExists($url, $consumerKey, $consumerSecret, $data)
    {
        $endpoint = $url . '/wp-json/wc/v3/products';

        $productName = $data['name'];
    
        $checkUrl = $endpoint . '?search=' . urlencode($productName);
    
        $ch = curl_init($checkUrl);
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($consumerKey . ':' . $consumerSecret)
        ]);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $responseData = json_decode($response, true);
    
            if ($httpCode == 200 && !empty($responseData)) {
                 return true;
            } elseif ($httpCode == 404 || empty($responseData)) {
                return false;
            }
        }
    }



    public function sendRequest($url, $consumerKey, $consumerSecret, $data, $reviews, $variations)
    {

        if(!$this->productExists($url, $consumerKey, $consumerSecret, $data)){

        $ch = curl_init($url.'/wp-json/wc/v3/products');

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($consumerKey . ':' . $consumerSecret)
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['name'])) {

                foreach($variations as $origin_variation)
                {
                    $origin_variation['attributes'][] = ['name' => 'Parent', 'option' => strval($responseData['id'])];
                    $this->sendVariation($url, $consumerKey, $consumerSecret, $responseData['id'], $origin_variation);
                }


                foreach($reviews as $review)
                {
                    $origin_review= [
                        "product_id" => $responseData['id'],
                        'review' => $review['review'],
                        'reviewer' => $review['reviewer'],
                        'reviewer_email' => "test@test.com",
                        'rating' => $review['rating'],
                        'date_created' => $review['date_created'],
                        'date_created_gmt' =>  $review['date_created_gmt']
                    ];

                    $this->sendReview($url, $consumerKey, $consumerSecret, $origin_review);
                    Sleep::for(15)->seconds();
                }

            } else {
                echo "API yanıtı beklenenden farklı:\n";
                var_dump($responseData);
            }
        }
        curl_close($ch);
        
       }

    }

    public function sendReview($url, $consumerKey, $consumerSecret, $review)
    {
        $ch = curl_init($url.'/wp-json/wc/v3/products/reviews');

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($consumerKey . ':' . $consumerSecret)
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($review));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['status'])) {
                echo 'Yorum Başarıyla eklendi.';
            } else {
                echo "API yanıtı beklenenden farklı:\n";
                var_dump($responseData);
            }
        }
        curl_close($ch);
        
    }

    public function sendVariation($url, $consumerKey, $consumerSecret, $productId, $variation)
    {

        $ch = curl_init($url.'/wp-json/wc/v3/products/'.$productId.'/variations');

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($consumerKey . ':' . $consumerSecret)
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variation));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['status'])) {
                echo 'Varyant Başarıyla eklendi.';
            } else {
                echo "Varyant API yanıtı beklenenden farklı:\n";
                var_dump($responseData);
            }
        }
        curl_close($ch);

    }
}
