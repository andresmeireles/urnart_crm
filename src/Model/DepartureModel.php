<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\ProductCart;
use App\Utils\Exceptions\CustomException;
use App\Utils\Andresmei\Form;
use App\Entity\ManualOrderReport;
use App\Entity\ManualProductCart;
use App\Entity\TravelTruckOrders;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class DepartureModel extends Model
{
    /**
     * @param Collection $unorganizedOrders
     * @param array $correctOrder
     * @return Collection
     */
    public function getCorrectOrderOfOrders(Collection $unorganizedOrders, array $correctOrder): Collection
    {
        $orderOfObjects = [];
        array_map(static function ($id) use (&$unorganizedOrders, &$orderOfObjects) {
            $unorganizedOrders->map(static function ($val) use (&$id, &$orderOfObjects) {
                if ($id === $val->getId()) {
                    $orderOfObjects[] = $val;
                }
            });
        }, array_keys($correctOrder));

        return new ArrayCollection($orderOfObjects);
    }

    /**
     * @param TravelTruckOrders $entityClass
     * @param Form $form
     * @param string $typeReport
     * @return string
     * @throws \Exception
     */
    public function generateAutomaticShowReportWithData(
        TravelTruckOrders $entityClass,
        Form $form,
        string $typeReport
    ): string {
        $report = $this->generateAutomaticReportWithData($entityClass, $form, $typeReport, 'show');

        return $report['template'];
    }

    /**
     * @param TravelTruckOrders $entityClass
     * @param Form $form
     * @param string $typeReport
     * @return array
     * @throws \Exception
     */
    public function generateAutomaticPdfReportWithData(
        TravelTruckOrders $entityClass,
        Form $form,
        string $typeReport
    ): array {
        return $this->generateAutomaticReportWithData($entityClass, $form, $typeReport, 'pdf');
    }

    /**
     * @param TravelTruckOrders $orderReport
     * @param Form $form
     * @return string
     * @throws \Exception
     */
    public function exportAllPdfReports(TravelTruckOrders $orderReport, Form $form): string
    {
        $this->generateAutomaticPdfReportWithData($orderReport, $form, 'tag');
        $this->generateAutomaticPdfReportWithData($orderReport, $form, 'fl');
        $this->generateAutomaticPdfReportWithData($orderReport, $form, 'receipt');
        $this->generateAutomaticPdfReportWithData($orderReport, $form, 'rb');
        $this->generateAutomaticPdfReportWithData($orderReport, $form, 'romaneio');
        $this->generateAutomaticPdfReportWithData($orderReport, $form, 'travel');
        $reportBuilderPath = __DIR__.'/../../public/reportBuilder/';
        $zipReportName = sprintf('%srelatorio.zip', $reportBuilderPath);
        $scanDir = (array) scandir($reportBuilderPath);
        $pdfFiles = [];
        foreach ($scanDir as $value) {
            if ($value && strpos($value, 'pdf')) {
                $pdfFiles[] = $value;
                continue;
            }
        }
        $zip = new \ZipArchive();
        if ($zip->open($zipReportName, \ZipArchive::CREATE)) {
            foreach ($pdfFiles as $pdfFile) {
                if (!$pdfFile) {
                    throw new \Exception('não é um arquivo.');
                }
                $zip->addFile(sprintf('%s/%s', $reportBuilderPath, $pdfFile), $pdfFile);
            }
            $zip->close();
        }
        array_map(static function ($file) use (&$reportBuilderPath) {
            if (strpos($file, 'pdf')) {
                $fileName = sprintf('%s/%s', $reportBuilderPath, $file);
                unlink($fileName);
            }
        }, $scanDir);

        return $zipReportName;
    }

    /**
     * @param TravelTruckOrders $entityClass
     * @param Form $form
     * @return array
     * @throws \Exception
     */
    public function generateAutomaticReportWithData(
        TravelTruckOrders $entityClass,
        Form $form,
        string $typeReport,
        string $methodType
    ): array {
        $uncorrectPositionedCorders = $entityClass->getOrderId();
        $orderOfElements = (array) $entityClass->getCheckedOrders();
        $collectionOfOrders = $this->getCorrectOrderOfOrders($uncorrectPositionedCorders, $orderOfElements);
        switch ($typeReport) {
            case 'tag':
                $data = $this->generateTagReportWithReportData($collectionOfOrders, $orderOfElements);
                break;
            case 'fl':
            case 'freightletter':
            case 'freight-letter':
                $typeReport = 'freight-letter';
                $data = $this->generateFreightLetterWithReportData($collectionOfOrders);
                break;
            case 'receipt':
                $data = $this->generateReceiptWithReportData($collectionOfOrders);
                break;
            case 'rb':
            case 'romaneioboard':
            case 'romaneio-board':
                $typeReport = 'romaneio-board';
                $data = $this->generateRomaneioWithReportData($collectionOfOrders);
                break;
            case 'romaneio':
            case 'rom':
                $typeReport = 'romaneio';
                $data = $this->generateRomaneioWithReportData($collectionOfOrders);
                break;
            case 'travel':
                if (!is_string($entityClass->getDriverName())) {
                    throw new \Exception('Nome do motorista não enviado');
                }
                $data = $this->generateTravelWithReportData($collectionOfOrders, $entityClass->getDriverName());
                break;
            default:
                throw new CustomException(
                    sprintf("O valor %s não é valido", $typeReport)
                );
                break;
        }

        return $form->returnSelectedFromType($methodType, $typeReport, $data);
    }

    /**
     * @param Collection $ordersCollection
     * @param array $checkOrders
     * @return array
     */
    public function generateTagReportWithReportData(
        Collection $ordersCollection,
        array $checkOrders
    ): array {
        $formData = [];
        $ordersCollection->map(static function ($value) use (&$formData, &$checkOrders) {
            $formData[] = [
                'city' => $value->getCustomerCity(),
                'name' => $value->getCustomerName(),
                'amount' => $value->getProductsAmount(),
                'check' => in_array($value->getId(), $checkOrders) ? $checkOrders[$value->getId()] : false
            ];
        });

        return $formData;
    }

    /**
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateFreightLetterWithReportData(
        Collection $ordersCollection
    ): array {
        $formData = [];
        $ordersCollection->map(static function ($value) use (&$formData) {
            /** @var ManualOrderReport $value */
            $formData[] = [
                'freight' => $value->getFreight(),
                'number' => $value->getId(),
                'clientName' => $value->getCustomerName(),
                'clientCity' => $value->getCustomerCity(),
                'clientState' => '',
                'orderNumber' => $value->getId()
            ];
        });
        
        return $formData;
    }

    /**
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateReceiptWithReportData(
        Collection $ordersCollection
    ): array {
        $formData = [];
        $ordersCollection->map(static function ($value) use (&$formData) {
            /** @var ManualOrderReport $value */
            $formData[] = [
                'clientName' => $value->getCustomerName(),
                'clientCity' => $value->getCustomerCity(),
                'clientState' => null,
                'orderNumber' => $value->getId(),
                'price' => $value->getOrderFinalPrice()
            ];
        });
        
        return $formData;
    }

    /**
     * romaneio board
     *
     * TEST OK
     *
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateRomaneioWithReportData(Collection $ordersCollection)
    {
        $formData = [];
        $urnG = ['210', '190', '180', '170', '160'];
        $urnM = ['150', '130', '110'];
        $urnP = ['090', '070', '050'];
        $ordersCollection->map(static function ($value) use (&$formData, &$urnG, &$urnM, &$urnP) {
            $urnGA = 0;
            $urnMA = 0;
            $urnPA = 0;
            /** @var ManualOrderReport $value */
            $value->getManualProductCarts()->map(static function ($prod) use (&$urnG, &$urnM, &$urnP, &$urnGA, &$urnMA, &$urnPA) {
                /** @var ManualProductCart $prod */
                $productName = $prod->getProductName();
                $productAmount = $prod->getProductAmount();
                array_map(static function ($amounts) use (&$urnGA, &$productName, &$productAmount) {
                    if (strpos($productName, $amounts)) {
                        $urnGA += $productAmount;
                    }
                }, $urnG);
                array_map(static function ($amounts) use (&$urnMA, &$productName, &$productAmount) {
                    if (strpos($productName, $amounts)) {
                        $urnMA += $productAmount;
                    }
                }, $urnM);
                array_map(static function ($amounts) use (&$urnPA, &$productName, &$productAmount) {
                    if (strpos($productName, $amounts)) {
                        $urnPA += $productAmount;
                    }
                }, $urnP);
            });
            $formData[] = [
                'name' => $value->getCustomerName(),
                'city' => $value->getCustomerCity(),
                'urnG' => $urnGA,
                'urnM' => $urnMA,
                'urnP' => $urnPA,
                'freight' => $value->getFreight(),
                'type' => $value->getPaymentType()
            ];
        });

        return $formData;
    }

    /**
     * travel
     * 
     * TEST OK
     *
     * @param string $driverName
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateTravelWithReportData(Collection $ordersCollection, string $driverName): array
    {
        $formData = [];
        $formData['driverName'] = $driverName;
        $data = $ordersCollection->map(static function ($value) {
            /** @var ManualOrderReport $value */
            return [
                'name' => $value->getCustomerName(),
                'city' => $value->getCustomerCity(),
            ];
        })->toArray();
        $formData['prod'] = $data;

        return $formData;
    }

    /**
     * @param Collection $ordersCollection
     * @param array $modelsNames
     * @return array
     */
    public function generateReportByModel(Collection $ordersCollection, array $modelsNames): array
    {
        $modelsName = array_values($modelsNames);
        $products = $ordersCollection->map(static function ($order) {
            /** @var ManualOrderReport $order */
            return $order->getManualProductCarts();
        });
        array_map(static function ($product) use (&$modelsName) {
            /** @var ManualProductCart $product */
            if (array_key_exists($product->getProductName(), $modelsName)) {
                $actualAmount = $modelsName[$product->getProductName()];
                $modelsName[$product->getProductName()] = $actualAmount + $product->getProductAmount();
            }
        }, $products);
        dump($modelsName);
        die;

        return $modelsName;
    }
}
