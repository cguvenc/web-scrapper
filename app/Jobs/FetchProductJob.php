<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\Back\HepsiburadaController;

class FetchProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $store_url;
    public $consumer_key;
    public $consumer_secret;
    public $url;
    public $reviewCount;
    public $categorieId;
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

    public function __construct(array $env, $url, $reviewCount, $categorieId)
    {
        $this->store_url = $env['store_url'];
        $this->consumer_key = $env['consumer_key'];
        $this->consumer_secret = $env['consumer_secret'];
        $this->url = $url;
        $this->reviewCount = $reviewCount;
        $this->categorieId = $categorieId;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $randomUserAgent = $this->availableUserAgents[array_rand($this->availableUserAgents)];

        $client = new Client([
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

        try {
            $response = $client->get($this->url);
            $content = $response->getBody()->getContents();
            $crawler = new Crawler($content);
    
            $name = $crawler->filter('#product-name')->text();
            $brand = $crawler->filter('.brand-name')->text();
            $price = $crawler->filter('[data-bind="markupText:\'currentPriceBeforePoint\'"]')->text();
            $price2 = $crawler->filter('[data-bind="markupText:\'currentPriceAfterPoint\'"]')->text();
            $description = $crawler->filter('#productDescriptionContent')->html() ?? '';
            $images = $crawler->filter('img[width="42"][height="42"].product-image');
            $reviews = $crawler->filter('[itemprop="review"]');
            $attributes = $crawler->filter('table.data-list.tech-spec tr');
            $variations = $crawler->filter('.variants-content');
            $origin_attributes = [];
            $colors = [];
            $sizes = [];
            $origin_images = [];
            $origin_reviews = [];
            $origin_variations = [];
            $grouped = [];


            $productType = $variations->count() > 1 ? 'variable' : 'simple';
    
            $images->each(function (Crawler $node) use (&$origin_images, &$client) {
                $src = $node->filter('img')->attr('src');
                $newSize = '600/800';
                $newsrc = preg_replace('/\/s\/\d+\/\d+\//', '/s/' . $newSize . '/', $src);
                if ($newsrc) {
                    $response = $client->get($newsrc);
                    if ($response->getStatusCode() == 200) {
                        $origin_images[] = ['src' =>  $newsrc];
                    }
                }
            });

            $reviews->each(function (Crawler $node, $i) use (&$origin_reviews) {
                if ($i < $this->reviewCount) {
                    try {
                        $name = $node->filter('[itemprop="name"]')->attr('content');
                        $rate = $node->filter('.star')->count();
                        $review = $node->filter('[itemprop="description"]')->text();
                        $date = $node->filter('[itemprop="datePublished"]')->attr('content');
                        $date = Carbon::createFromFormat('Y-m-d', $date);

                        $origin_reviews[] = [
                            'product_id' => 22,
                            'review' => $review,
                            'reviewer' => $name,
                            'reviewer_email' => 'test@test.com',
                            'rating' => $rate,
                            'date_created' => $date->toIso8601String(),
                            'date_created_gmt' => $date->setTimezone('GMT')->toIso8601String()
                        ];

                    } catch (\Exception $e) {
                        return true;
                    }
                }
            });


            $crawler->filter('table.data-list.tech-spec:not(.hidden) tr')->each(function (Crawler $node) use (&$origin_attributes) {
                $name = $node->filter('th')->text();
                $value = $node->filter('td span')->count() ? $node->filter('td span')->text() : $node->filter('td a')->text();

                if($name != 'Yurt Dışı Satış' && $name != 'Stok Kodu')
                {
                    $origin_attributes[] = [
                        'name' => $name,
                        'visible' => true,
                        'variation' => false,
                        'has_archive' => true,
                        'options' => [$value]
                    ];
                }
            });

            $values = 
            

            $variations->each(function (Crawler $node) use ($price, $price2, &$grouped) {
                $label = $node->filter('input')->attr('name');
                $value = $node->filter('input')->attr('value');
                $src = $node->filter('.variant-image')->attr('src');
                $newSize = '600/800';
                $newsrc = preg_replace('/\/s\/\d+\/\d+\//', '/s/' . $newSize . '/', $src);
                $price = $node->filter('.variant-property-price')->text() ?? $price . '.' .$price2;

                $found = false;

                foreach ($grouped as &$group) {
                    if ($group['name'] === $label) {
                        $group['options'][] = [
                            'value' => $value,
                            'image' => $newsrc,
                            'price' => $price,
                        ];
                        $found = true;
                        break;
                    }
                }
            
                if (!$found) {
                    $grouped[] = [
                        'name' => $label,
                        'options' => [
                            [
                                'value' => $value,
                                'image' => $newsrc,
                                'price' => $price,
                            ],
                        ],
                    ];
                }
            });


            if(count($grouped) > 0)
            {
                $values = [];

               foreach($grouped as $key => $group)
               {

                    foreach ($group['options'] as $option)
                    {

                        $values[] = $option['value'];
                    }

                $origin_attributes[] = [
                    'name' => $group['name'],
                    'visible' => true,
                    'variation' => true,
                    'options' => $values
                ];

                if(is_array($group['options']))
                {
                    foreach ($group['options'] as $option) {
                        $origin_variations[] = [
                            'attributes' => [
                                [
                                    'sku' => date('YmdHis'),
                                    'name' => $group['name'],
                                    'option' => $option['value']
                                ],
                            ],
                            'image' =>  [
                                'src' => $option['image']
                            ],
                            'regular_price' => $option['price'],
                            'visible' => true,
                            'variation' => true,
                        ];
                    }
                    }
               }

            }

            $origin_attributes[] = [
                'name' => 'Marka',
                'visible' => true,
                'variation' => false,
                'has_archive' => true,
                'options' => [$brand]
            ];


            $data = [
                'name' => $name,
                'type' => $productType,
                'regular_price' => $price . '.' . $price2,
                'description' => $description,
                'short_description' => $description,
                'categories' => [
                    [
                        'id' => $this->categorieId
                    ],
                ],
                'images' => $origin_images,
                'attributes' => $origin_attributes
            ];

            Log::info($data);

            $this->sendRequest($data, $origin_reviews, $origin_variations);
    
        } catch (\Exception $e) {
            Log::error('FetchProductJob failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendRequest($data, $reviews, $variations)
    {
        $hepsiController = new HepsiburadaController();
        $hepsiController->sendRequest($this->store_url, $this->consumer_key, $this->consumer_secret, $data,$reviews, $variations);
    }
}
