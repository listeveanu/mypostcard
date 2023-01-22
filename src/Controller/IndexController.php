<?php

declare(strict_types=1);

namespace Cezar\Mypostcard\Controller;

use Cezar\Mypostcard\Services\HttpService;
use NumberFormatter;

class IndexController extends BaseController {

    public static string $designList = 'https://appdsapi-6aa0.kxcdn.com/content.php?lang=de&json=1&search_text=berlin&currencyiso=EUR';
    private static string $productOptionsPrices = 'https://www.mypostcard.com/mobile/product_prices.php?json=1&type=get_postcard_products&currencyiso=EUR&store_id=';
    private static string $thumbUrl = 'https://appdsapi-6aa0.kxcdn.com/card_front_covers/thumb/';
    const GREET_CARD = 'Greetcard';
    const POSTCARD_SET = 'Postcard_Set';
    const DISPLAY_COUNT = 25;

    /**
     * @param array $request
     * @return false|string
     */
    public function index(array $request): false|string
    {
        $httpService = new HttpService();
        $designList = $httpService->request(self::$designList);
        $designs = $designList['content'];

        $currentDesigns = $this->preparePagination($designs, $request);

        foreach ($currentDesigns as $index => $design) {
            $fileName = str_replace(self::$thumbUrl, '/thumbnail/', $design['thumb_url']);
            $fileNameWithoutExtension = str_replace('.jpg', '', $fileName);
            $design['thumb_local'] = $fileNameWithoutExtension;
            $design['price_local'] = $this->formatPrice($design['price'], $design['currencyiso']);

            $productOptions = $this->getProductOptions($httpService, $design['id']);

            if ($design['is_greeting_card'] === true) {
                $envelopePrice = $this->getEnvelopePrice($productOptions);
                if ($envelopePrice) {
                    $design['price_local'] = $this->formatPrice(((float)$design['price'] + $envelopePrice), $design['currencyiso']);
                }
            }

            if ($design['is_postcard'] === true) {
                $design['productOptionsPrice'] = $this->getProductOptionsPrice($productOptions, $design['currencyiso']);
            }

            $currentDesigns[$index] = $design;
        }

        $this->view->setTemplate('index');
        $this->view->assign('designs', $currentDesigns);


        return $this->render();
    }

    /**
     * @param object $httpService
     * @param $designId
     * @return mixed
     */
    private function getProductOptions(object $httpService, $designId): mixed
    {
        return $httpService->request(self::$productOptionsPrices . $designId)['products'];
    }

    /**
     * @param $productOptions
     * @return float|false
     */
    private function getEnvelopePrice($productOptions): float|false
    {
        foreach ($productOptions as $productOption) {
            if ($productOption['assignedtype'] == self::GREET_CARD) {
                return (float) $productOption['product_options']['Envelope']['price'];
            }
        }

        return false;
    }


    /**
     * @param $productOptions
     * @param $currencyIso
     * @return array
     */
    private function getProductOptionsPrice($productOptions, $currencyIso): array
    {
        $optionsPrice = [];
        foreach ($productOptions as $productOption) {
            if ($productOption['assignedtype'] == self::POSTCARD_SET) {
                foreach ($productOption['product_options'] as $quantity => $option) {
                    $optionsPrice[$quantity]['price_formatted'] = $this->formatPrice($option['price'], $currencyIso);
                    $optionsPrice[$quantity]['price_per_card'] = $this->formatPrice(($option['price'] / $quantity), $currencyIso);
                }
            }
        }

        return $optionsPrice;
    }

    /**
     * @param $designs
     * @param $request
     * @return array
     */
    private function preparePagination($designs, $request): array
    {
        $chunkDesigns = array_chunk($designs, self::DISPLAY_COUNT, true);
        $totalPages = count($chunkDesigns);

        $page = (array_key_exists('page', $request) && $request['page'] <= $totalPages) ? $request['page'] : 1;
        $indexPage = ($page > 0) ? ($page - 1) : $page;

        $this->view->assign('pages', $totalPages);
        $this->view->assign('currentPage', $page);

        return $chunkDesigns[$indexPage];
    }

    /**
     * @param $price
     * @param $currencyIso
     * @return string
     */
    private function formatPrice($price, $currencyIso): string
    {
        $fmt = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);

        return $fmt->formatCurrency(floatval($price), $currencyIso);

    }
}
