<?php declare(strict_types=1);

namespace App\Model;

use App\Utils\Andresmei\Form;
use App\Entity\TravelTruckOrders;
use App\Utils\Exceptions\CustomException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class DepartureModel extends Model
{
    public function getCorrectOrderOfOrders(Collection $unorganizedOrders, array $correctOrder): Collection
    {
        $orderOfObjects = [];
        array_map(function ($id) use (&$unorganizedOrders, &$orderOfObjects) {
            $unorganizedOrders->map(function ($val) use (&$id, &$orderOfObjects) {
                if ($id === $val->getId()) {
                    $orderOfObjects[] = $val;
                }
            });
        }, array_keys($correctOrder));

        return new ArrayCollection($orderOfObjects);
    }

    /**
     * Seleciona o gerador do ventilador
     *
     * @param TravelTruckOrders $entityClass
     * @param Form $form
     * @return string
     */
    public function generateAutomaticShowReportWithData(
        object $entityClass,
        Form $form,
        string $typeReport
    ): string {
        $uncorrectPositionedCorders = $entityClass->getOrderId();
        $collectionOfOrders = $this->getCorrectOrderOfOrders($uncorrectPositionedCorders, $entityClass->getCheckedOrders());
        switch ($typeReport) {
            case 'tag':
                $data = $this->generateTagReportWithReportData($collectionOfOrders, $entityClass->getCheckedOrders());
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
                $data = $this->generateRomaneioBoardWithReportData($collectionOfOrders);
                break;
            case 'romaneio':
            case 'rom':
                $typeReport = 'romaneio';
                $data = $this->generateRomaneioWithReportData($collectionOfOrders);
                break;
            case 'travel':
                $data = $this->generateTravelWithReportData($collectionOfOrders, $entityClass->getDriverName());
                break;
            default:
                throw new CustomException(
                    sprintf("O valor %s não é valido", $typeReport)
                );
                break;
        }
        $result = $form->returnSelectedFromType('show', $typeReport, $data);
        return $result['template'];
    }

    /**
     * Automatic tag report data creation
     *
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateTagReportWithReportData(
        Collection $ordersCollection,
        array $checkOrders
    ): array {
        $formData = [];
        $ordersCollection->map(function ($value) use (&$formData, &$checkOrders) {
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
     * Freight letter
     *
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateFreightLetterWithReportData(
        Collection $ordersCollection
    ): array {
        $formData = [];
        /** @var \App\Entity\ManualOrderReport $value */
        $ordersCollection->map(function ($value) use (&$formData) {
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
     * receipt
     *
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateReceiptWithReportData(
        Collection $ordersCollection
    ): array {
        $formData = [];
        $ordersCollection->map(function ($value) use (&$formData) {
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
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateRomaneioBoardWithReportData(Collection $ordersCollection)
    {
        $formData = [];
        $urnG = ['210', '190', '180', '170', '160'];
        $urnM = ['150', '130', '110'];
        $urnP = ['090', '070', '050'];
        $urnGA = 0;
        $urnMA = 0;
        $urnPA = 0;
        $ordersCollection->map(function ($value) use (&$formData, &$urnG, &$urnM, &$urnP, &$urnGA, &$urnMA, &$urnPA) {
            $value->getManualProductCarts()->map(function ($prod) use (&$urnG, &$urnM, &$urnP, &$urnGA, &$urnMA, &$urnPA) {
                $productName = $prod->getProductName();
                $productAmount = $prod->getProductAmount();
                array_map(function ($amounts) use (&$urnGA, &$productName, &$productAmount) {
                    $urnGA += strpos($productName, $amounts) ? $productAmount : 0;
                }, $urnG);
                array_map(function ($amounts) use (&$urnMA, &$productName, &$productAmount) {
                    $urnMA += strpos($productName, $amounts) ? $productAmount : 0;
                }, $urnM);
                array_map(function ($amounts) use (&$urnPA, &$productName, &$productAmount) {
                    $urnPA += strpos($productName, $amounts) ? $productAmount : 0;
                }, $urnP);
            });
            $formData[] = [
                'name' => $value->getCustomerName(),
                'city' => $value->getCustomerCity(),
                'urnG' => $urnGA,
                'urnM' => $urnMA,
                'urnP' => $urnPA
            ];
        });

        return $formData;
    }

    /**
     * romaneio
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
        $urnGA = 0;
        $urnMA = 0;
        $urnPA = 0;
        $ordersCollection->map(function ($value) use (&$formData, &$urnG, &$urnM, &$urnP, &$urnGA, &$urnMA, &$urnPA) {
            $value->getManualProductCarts()->map(function ($prod) use (&$urnG, &$urnM, &$urnP, &$urnGA, &$urnMA, &$urnPA) {
                $productName = $prod->getProductName();
                $productAmount = $prod->getProductAmount();
                array_map(function ($amounts) use (&$urnGA, &$productName, &$productAmount) {
                    $urnGA += strpos($productName, $amounts) ? $productAmount : 0;
                }, $urnG);
                array_map(function ($amounts) use (&$urnMA, &$productName, &$productAmount) {
                    $urnMA += strpos($productName, $amounts) ? $productAmount : 0;
                }, $urnM);
                array_map(function ($amounts) use (&$urnPA, &$productName, &$productAmount) {
                    $urnPA += strpos($productName, $amounts) ? $productAmount : 0;
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
     * @param object $departureReport
     * @param Collection $ordersCollection
     * @return array
     */
    public function generateTravelWithReportData(Collection $ordersCollection, string $driverName): array
    {
        $formData = [];
        $formData['driverName'] = $driverName;
        $data = $ordersCollection->map(function ($value) {
            return [
                'name' => $value->getCustomerName(),
                'city' => $value->getCustomerCity(),
            ];
        })->toArray();
        $formData['prod'] = $data;

        return $formData;
    }
}