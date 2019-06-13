<?php declare(strict_types=1);

namespace App\Model;

use App\Utils\Andresmei\Form;
use App\Entity\TravelTruckOrders;
use App\Utils\Exceptions\CustomException;
use Doctrine\Common\Collections\Collection;

class DepartureModel extends Model
{
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
        $collectionOfOrders = $entityClass->getOrderId();
        switch ($typeReport) {
            case 'tag':
                $data = $this->generateTagReportWithReportData($collectionOfOrders);
                break;
            case 'freight-letter':
                $data = $this->generateFreightLetterWithReportData($collectionOfOrders);
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
        Collection $ordersCollection
    ): array {
        $formData = [];
        $ordersCollection->map(function ($value) use (&$formData) {
            $formData[] = [
                'city' => $value->getCustomerCity(),
                'name' => $value->getCustomerName(),
                'amount' => $value->getProductsAmount(),
                'check' => false
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
        
        return $formData;
    }
}