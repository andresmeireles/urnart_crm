<?php

declare(strict_types=1);

namespace App\Model;

use App\Document\Romaneio;
use App\Entity\Expenses;
use App\Entity\TravelAccountability;
use App\Entity\TravelEntry;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Exceptions\CustomException;
use App\Utils\Exceptions\NotFoundParameterException as ExceptionsNotFoundParameterException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

final class FormModel extends Model
{
    /**
     * @param array $data
     * @param string $path
     * @return FlashResponse
     * @throws \Exception
     */
    public function saveReport(array $data, string $path)
    {
        unset($data['save']);
        $data['created'] = (new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')))->format('d-m-Y h:m:s');
        $name = md5((string) rand());
        $data['name'] = $name;
        $filesystem = new Filesystem();
        $fileData = Yaml::dump($data);
        $fullPath = sprintf('%s/%s.yaml', $path, $name);
        $filesystem->dumpFile($fullPath, $fileData);

        return new FlashResponse(200, 'success', 'Arquivo salvo com sucesso!');
    }

    public function saveReportDocument(array $data, DocumentManager $dcm): FlashResponse
    {
        unset($data['save']);
        if ($data['_id'] === null) {
            return $this->saveNewReportDocument($data, $dcm);
        }

        return $this->editReportDocument($data, $dcm);
    }

    public function saveNewReportDocument(array $data, DocumentManager $dcm): FlashResponse
    {
        $data['created'] = (new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')))->getTimestamp();
        $items = array_filter(array_values($data), static function ($element) {
            if (is_array($element)) {
                return $element;
            }
        });

        try {
            $newRomaneio = new Romaneio($data['driverName'], $data['type'], $items, $data['created']);
            $dcm->persist($newRomaneio);
            $dcm->flush();

            return new FlashResponse(200, 'success', sprintf('Arquivo salvo com sucesso! Id inserido é %s', $newRomaneio->getId()));
        } catch (\Exception $err) {
            throw new CustomException(
                sprintf(
                    '%s. Linha: %s. Arquivo: %s.',
                    $err->getMessage(),
                    $err->getFile(),
                    $err->getLine(),
                )
            );
        }
    }

    public function editReportDocument(array $data, DocumentManager $dcm): FlashResponse
    {
        $items = array_filter(array_values($data), static function ($element) {
            if (is_array($element)) {
                return $element;
            }
        });

        try {
            $document = $dcm->getRepository(Romaneio::class)->find($data['_id']);
            $newRomaneio = $document->edit($data['driverName'], $data['type'], $items);
            $dcm->merge($newRomaneio);
            $dcm->flush();

            return new FlashResponse(200, 'success', sprintf('Arquivo salvo com sucesso! Id inserido é %s', $newRomaneio->getId()));
        } catch (\Exception $err) {
            throw new CustomException(
                sprintf(
                    '%s. Linha: %s. Arquivo: %s.',
                    $err->getMessage(),
                    $err->getFile(),
                    $err->getLine(),
                )
            );
        }
    }

    // /**
    //  * @return FlashResponse
    //  * @throws \Exception
    //  */
    // public function reportResolver(string $formName, array $data): FlashResponse
    // {
    //     switch ($formName) {
    //         case 'travel-report':
    //             return $this->runAccountabilityReport($data);
    //         default:
    //             throw new \Exception(sprintf('Relatorio com nome %s não existe', $formName));
    //     }
    // }

    // /**
    //  * @param string $formName
    //  * @param array $data
    //  * @param int $reportId
    //  * @return FlashResponse
    //  * @throws \Exception
    //  */
    // public function editReportResolver(string $formName, array $data, int $reportId): FlashResponse
    // {
    //     switch ($formName) {
    //         case 'travel-report':
    //             $entity = $this->entityManager
    //                 ->getRepository(TravelAccountability::class)
    //                 ->find($reportId);
    //             return $this->editAcountabilityReport($entity, $data);
    //         default:
    //             throw new \Exception(sprintf('Relatorio editavel com nome %s não existe', $formName));
    //     }
    // }

    // /**
    //  * @param object $entity
    //  * @param array $data
    //  * @return  FlashResponse
    //  * @throws \Exception
    //  */
    // public function editAcountabilityReport(object $entity, array $data): FlashResponse
    // {
    //     /** @var TravelAccountability $report */
    //     $report = $entity; // over ride type hint for intellisense
    //     $entityManager = $this->entityManager;
    //     $nestedData = new NestedArraySeparator($data);
    //     $entries = $nestedData->getAssoativeArrayGroup('customerArr');
    //     $expenses = $nestedData->getAssoativeArrayGroup('despesas');
    //     $accountabilityData = $nestedData->getSimpleArray();
    //     try {
    //         $report->setDriverName($accountabilityData['driver-name']);
    //         $km = (float) $accountabilityData['km-out'];
    //         $report->setDepartureKm($km);
    //         $km = (float) $accountabilityData['km-in'];
    //         $report->setArrivalKm($km);
    //         $cash = $accountabilityData['caixa'] ?? 0;
    //         $report->setCash((float) $cash);
    //         $report->setComment($accountabilityData['comment']);
    //         $report->setDepartureDate(
    //             new \DateTime($accountabilityData['dt-out'], new \DateTimeZone('America/Sao_Paulo'))
    //         );
    //         $report->setArrivalDate(
    //             new \DateTime($accountabilityData['dt-in'], new \DateTimeZone('America/Sao_Paulo'))
    //         );
    //         if ($entries === null) {
    //             throw new ExceptionsNotFoundParameterException(sprintf('Varaibel %s não existe', ${$entity}));
    //         }
    //         $this->editTravelEntryReport($entries, $report);
    //         if ($expenses !== null) {
    //             $this->editTravelExpenseReport($expenses, $report);
    //         }
    //         $entityManager->persist($report);
    //         $entityManager->flush();
    //     } catch (\Exception $e) {
    //         throw new \Exception(sprintf('%s', $e->getMessage()));
    //     }

    //     return new FlashResponse(
    //         200,
    //         'success',
    //         sprintf('Relatório %s foi editado com sucesso.', $report->getId())
    //     );
    // }

    // /**
    //  * @param array $data
    //  * @return FlashResponse
    //  * @throws \Exception
    //  */
    // public function runAccountabilityReport(array $data/* , ?bool $isEdit = false */): FlashResponse
    // {
    //     $entityManager = $this->entityManager;
    //     $accountability = new TravelAccountability();
    //     /* dump($data);
    //     die; */
    //     $array = new NestedArraySeparator($data);
    //     $accountabilityData = $array->getSimpleArray();
    //     $entries = $array->getAssoativeArrayGroup('customerArr');
    //     $expenses = $array->getAssoativeArrayGroup('despesas');

    //     try {
    //         $accountability->setDriverName($accountabilityData['driver-name']);
    //         $km = (float) $accountabilityData['km-out'];
    //         $accountability->setDepartureKm($km);
    //         $km = (float) $accountabilityData['km-in'];
    //         $accountability->setArrivalKm($km);
    //         $cash = $accountabilityData['caixa'] ?? 0;
    //         $accountability->setCash((float) $cash);
    //         $accountability->setComment($accountabilityData['comment']);
    //         $accountability->setDepartureDate(
    //             new \DateTime($accountabilityData['dt-out'], new \DateTimeZone('America/Sao_Paulo'))
    //         );
    //         $accountability->setArrivalDate(
    //             new \DateTime($accountabilityData['dt-in'], new \DateTimeZone('America/Sao_Paulo'))
    //         );

    //         if (!is_array($entries)) {
    //             throw new CustomException('Tipo de variavel incorreto.');
    //         }

    //         foreach ($entries as $value) {
    //             $this->saveTravelEntries($value, $accountability);
    //         }

    //         if (is_array($expenses)) {
    //             foreach ($expenses as $value) {
    //                 $this->saveTravelExpenseReport($value, $accountability);
    //             }
    //         }

    //         $entityManager->persist($accountability);
    //         $entityManager->flush();
    //     } catch (\Exception $e) {
    //         throw new \Exception($e->getMessage(), $e->getLine());
    //     }

    //     return new FlashResponse(
    //         200,
    //         'success',
    //         'Relatorio criado com suceeso.'
    //     );
    // }

    // /**
    //  * @param array $expenses
    //  * @param TravelAccountability $travelAccountability
    //  */
    // public function editTravelExpenseReport(array $expenses, TravelAccountability $travelAccountability): void
    // {
    //     $entityManager = $this->entityManager;
    //     $rmExpenses = $entityManager->getRepository(Expenses::class)->findBy([
    //         'idAccountability' => $travelAccountability->getId(),
    //     ]);
    //     foreach ($rmExpenses as $expense) {
    //         $entityManager->remove($expense);
    //         $entityManager->flush();
    //     }

    //     foreach ($expenses as $singleExpense) {
    //         if ($singleExpense['name'] === '') {
    //             continue;
    //         }
    //         $this->saveTravelExpenseReport($singleExpense, $travelAccountability);
    //     }
    // }

    // /**
    //  * @param array $entries
    //  * @param TravelAccountability $travelAccountability
    //  */
    // public function editTravelEntryReport(array $entries, TravelAccountability $travelAccountability): void
    // {
    //     $entityManager = $this->entityManager;
    //     $rmEntry = $entityManager->getRepository(TravelEntry::class)->findBy([
    //         'idTravelAccountability' => $travelAccountability->getId(),
    //     ]);
    //     foreach ($rmEntry as $entry) {
    //         $entityManager->remove($entry);
    //         $entityManager->flush();
    //     }

    //     foreach ($entries as $singleEntry) {
    //         $this->saveTravelEntries($singleEntry, $travelAccountability);
    //     }
    // }

    // /**
    //  * @param array $data
    //  * @param TravelAccountability $accountability
    //  * @param bool|null $isCommit
    //  */
    // public function saveTravelExpenseReport(
    //     array $data,
    //     TravelAccountability $accountability,
    //     ?bool $isCommit = false
    // ): void {
    //     $entityManager = $this->entityManager;
    //     $expense = new Expenses();
    //     $expense->setNome($data['name']);

    //     $value = (float) $data['value'];
    //     $expense->setValor($value ?? 0);

    //     $expense->setIdAccountability($accountability);
    //     $entityManager->persist($expense);
    //     //$entityManager->flush();
    //     if ($isCommit) {
    //         $entityManager->flush();
    //     }
    // }

    // /**
    //  * @param array $data
    //  * @param TravelAccountability $accountability
    //  * @param bool|null $isCommit
    //  */
    // public function saveTravelEntries(
    //     array $data,
    //     TravelAccountability $accountability,
    //     ?bool $isCommit = false
    // ): void {
    //     $entityManager = $this->entityManager;
    //     $entry = new TravelEntry();
    //     $entry->setNome($data['customer']);

    //     $value = (float) $data['order-value'];
    //     $entry->setOrderValue($value ?? 0);

    //     $value = (float) $data['freight'];
    //     $entry->setFreight($value ?? 0);

    //     $value = (float) $data['check'];
    //     $entry->setCheckValue($value ?? 0);

    //     $value = (float) $data['freight'];
    //     $entry->setFreight($value ?? 0);

    //     $value = (float) $data['other'];
    //     $entry->setByCountValue($value ?? 0);

    //     $entry->setIdTravelAccountability($accountability);

    //     $entityManager->persist($entry);
    //     //$entityManager->flush();
    //     if ($isCommit) {
    //         $entityManager->flush();
    //     }
    // }
}
