<?php

declare(strict_types=1);

namespace Cezar\Mypostcard\Controller;

use Cezar\Mypostcard\Services\HttpService;
use TCPDF;

class PdfController extends BaseController {

    /**
     * @param array $request
     * @return void
     */
    public function index(array $request): void
    {
        $httpService = new HttpService();
        $designList = $httpService->request(IndexController::$designList);
        $designs = $designList['content'];

        $printUrl = '';
        $filename = '';
        foreach ($designs as $design) {
            if ($design['id'] === $request['id']) {
                $printUrl = $design['print_url'];
                $filename = $design['url_slug'] . '.pdf';
                break;
            }
        }

        list($width, $height, $orientation) = $this->getImageProperties($printUrl);

        $tcpdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->constructPdf($tcpdf);

        $tcpdf->AddPage($orientation, [$width, $height]);

        $imageData = file_get_contents($printUrl);
        $tcpdf->Image('@' . $imageData, 0, 0, $width, $height);

        $tcpdf->Output($filename);
    }

    /**
     * @param string $printUrl
     * @return array
     */
    private function getImageProperties(string $printUrl): array
    {
        list($width, $height) = getimagesize($printUrl);

        $orientation = 'L';
        if ($width < $height) {
            $orientation = 'P';
        } elseif ($width == $height) {
            $orientation = 'S';
        }

        return array($width, $height, $orientation);
    }

    /**
     * @param object $tcpdf
     * @return void
     */
    private function constructPdf(object $tcpdf): void
    {
        $tcpdf->setPrintHeader(false);
        $tcpdf->setPrintFooter(false);
        $tcpdf->setPDFVersion('1.3');
        $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $tcpdf->SetMargins(0, 0, 0);
        $tcpdf->SetAutoPageBreak(false, 0);
        $tcpdf->setJPEGQuality(100);
    }
}