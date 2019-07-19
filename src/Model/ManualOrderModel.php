<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\ManualOrderReport;
use App\Entity\ManualProductCart;
use App\Entity\PaymentType;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\NestedArraySeparator;
use Respect\Validation\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**i
 * Class ManualOrderController
 * @package App\Model
 */
final class ManualOrderModel extends Model
{
    /**
     * @var string|null
     */
    private $errors = null;

    public function createNewOrderModel(array $orderData): FlashResponse
    {
        return $this->execute($orderData, new ManualOrderReport());
    }

    public function editOrderModelById(int $orderId, array $orderData): FlashResponse
    {
        $manualOrder = $this->entityManager->getRepository(ManualOrderReport::class)->find($orderId);
        $dqlStringConsult = sprintf(
            'DELETE FROM %s e WHERE e.manualOrderReport = %s',
            ManualProductCart::class,
            $orderId
        );
        $this->dqlQuery($dqlStringConsult);

        return $this->execute($orderData, $manualOrder);
    }

    private function execute(array $orderData, ManualOrderReport $orderObject): FlashResponse
    {
        $successMessage = $orderObject->getId() === null ?
            'Pedido inserido com sucesso.' :
            sprintf('Pedido %s editado com sucesso.', $orderObject->getId());
        try {
            $this->addDataOnManualOrder($orderData, $orderObject);

            return new FlashResponse(Response::HTTP_OK, 'success', $successMessage);
        } catch (\Exception $err) {
            return new FlashResponse(Response::HTTP_NON_AUTHORITATIVE_INFORMATION, 'error', $err->getMessage());
        }
    }

    private function addDataOnManualOrder(array $data, ManualOrderReport $orderObject): void
    {
        $entityManager = $this->entityManager;
        $products = (new NestedArraySeparator($data))->getArrayInArray();
        $orderObject->setCustomerName($data['clientName']);
        $orderObject->setCustomerCity($data['clientCity']);
        $orderObject->setFreight((float) $data['freight']);
        $orderObject->setComments($data['observation']);
        $orderObject->setDiscount((float) $data['discount']);
        $orderObject->setPort($data['port']);
        $orderObject->setTransporter($data['transporter']);
        $payment = $entityManager->getRepository(PaymentType::class)->find($data['formPg']);
        $orderObject->setPaymentType($payment);
        $this->addProductsInOrder($products, $orderObject);
        $this->checkIfErrorExists($orderObject);
        if ($this->errors !== null) {
            throw new ValidationException($this->errors);
        }
        $entityManager->persist($orderObject);
        $entityManager->flush();
    }

    private function addProductsInOrder(array $products, ManualOrderReport $manualOrderReport): void
    {
        $entityManager = $this->entityManager;
        array_map(function ($prod) use (&$entityManager, &$manualOrderReport) {
            $productCart = new ManualProductCart();
            $productCart->setProductName($prod['model']);
            $productCart->setProductAmount((int) $prod['amount']);
            $productCart->setProductPrice((float) $prod['money']);
            $productCart->setManualOrderReport($manualOrderReport);
            $this->checkIfErrorExists($productCart);
            $entityManager->persist($productCart);
        }, $products);
    }

    private function checkIfErrorExists(object $entityObject): void
    {
        $errors = $this->getValidator()->executeClassValidation($entityObject);
        if ($errors !== null) {
            $errMessage = '';
            foreach ($errors as $error) {
                $errMessage .= $error[0] . ' ';
                $this->errors = $errMessage;
            }
        }
    }
}