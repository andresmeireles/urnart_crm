<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\NotFoundParameterException;
use App\Entity\Expenses;
use App\Entity\TravelAccountability;
use App\Entity\TravelEntry;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Exceptions\CustomException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class FormModel extends Model
{
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

    public function reportResolver(string $formName, array $data): FlashResponse
    {
        switch ($formName) {
            case 'travel-report':
                //$isEdit = $data['edit'] ?? false;
                return $this->runAccountabilityReport($data/* , $isEdit */);
            default:
                throw new \Exception(sprintf('Relatorio com nome %s não existe', $formName));
        }
    }

    public function editReportResolver(string $formName, array $data, int $reportId): FlashResponse
    {
        switch ($formName) {
            case 'travel-report':
                $entity = $this->em
                                ->getRepository(TravelAccountability::class)
                                ->find($reportId);
                return $this->editAcountabilityReport($entity, $data);
            default:
                throw new \Exception(sprintf('Relatorio editavel com nome %s não existe', $formName));
        }
    }

    /**
     * Editar relatório.
     *
     * @param object $entity
     * @param array  $data
     *
     * @return  FlashResponse
     */
    public function editAcountabilityReport(object $entity, array $data): FlashResponse
    {
        /** @var TravelAccountability */
        $report = $entity; // over ride type hint for intellisense
        $entityManager = $this->em;
        $nestedData = new NestedArraySeparator($data);
        $entries = $nestedData->getAssoativeArrayGroup('customerArr');
        $expenses = $nestedData->getAssoativeArrayGroup('despesas');
        $accountabilityData = $nestedData->getSimpleArray();
        try {
            $report->setDriverName($accountabilityData['driver-name']);
            $km = (float) $accountabilityData['km-out'];
            $report->setDepartureKm($km);
            $km = (float) $accountabilityData['km-in'];
            $report->setArrivalKm($km);
            $cash = $accountabilityData['caixa'] ?? 0;
            $report->setCash((float) $cash);
            $report->setComment($accountabilityData['comment']);
            $report->setDepartureDate(
                new \DateTime($accountabilityData['dt-out'], new \DateTimeZone('America/Sao_Paulo'))
            );
            $report->setArrivalDate(
                new \DateTime($accountabilityData['dt-in'], new \DateTimeZone('America/Sao_Paulo'))
            );
            if (null === $entries) {
                throw new NotFoundParameterException(sprintf('Varaibel %s não existe', $$entity));
            }
            $this->editTravelEntryReport($entries, $report);
            if ($expenses !== null) {
                $this->editTravelExpenseReport($expenses, $report);
            }
            $entityManager->persist($report);
            $entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception(sprintf('%s', $e->getMessage()));
        }

        return new FlashResponse(200, 'success', sprintf('Relatório %s foi editado com sucesso.', $report->getId()));
    }

    public function runAccountabilityReport(array $data/* , ?bool $isEdit = false */): FlashResponse
    {
        $entityManager = $this->em;
        $accountability = new TravelAccountability();
        $array = new NestedArraySeparator($data);
        $accountabilityData = $array->getSimpleArray();
        $entries = $array->getAssoativeArrayGroup('customerArr');
        $expenses = $array->getAssoativeArrayGroup('despesas');

        try {
            $accountability->setDriverName($accountabilityData['driver-name']);
            $km = (float) $accountabilityData['km-out'];
            $accountability->setDepartureKm($km);
            $km = (float) $accountabilityData['km-in'];
            $accountability->setArrivalKm($km);
            $cash = $accountabilityData['caixa'] ?? 0;
            $accountability->setCash((float) $cash);
            $accountability->setComment($accountabilityData['comment']);
            $accountability->setDepartureDate(
                new \DateTime($accountabilityData['dt-out'], new \DateTimeZone('America/Sao_Paulo'))
            );
            $accountability->setArrivalDate(
                new \DateTime($accountabilityData['dt-in'], new \DateTimeZone('America/Sao_Paulo'))
            );

            if (!is_array($entries)) {
                throw new CustomException("Tipo de variavel incorreto.");
            }

            foreach ($entries as $value) {
                $this->saveTravelEntries($value, $accountability);
            }

            if (is_array($expenses)) {
                foreach ($expenses as $value) {
                    $this->saveTravelExpenseReport($value, $accountability);
                }
            }
            
            $entityManager->persist($accountability);
            $entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getLine());
        }

        return new FlashResponse(200, 'success', 'Relatorio criado com suceeso.');
    }

    public function editTravelExpenseReport(array $expenses, TravelAccountability $travelAccountability): void
    {
        $entityManager = $this->em;
        $rmExpenses = $entityManager->getRepository(Expenses::class)->findBy([
            'idAccountability' => $travelAccountability->getId(),
        ]);
        foreach ($rmExpenses as $expense) {
            $entityManager->remove($expense);
            $entityManager->flush();
        }

        foreach ($expenses as $singleExpense) {
            if ($singleExpense['name'] === '') {
                continue;
            }
            $this->saveTravelExpenseReport($singleExpense, $travelAccountability);
        }
    }

    public function editTravelEntryReport(array $entries, TravelAccountability $travelAccountability): void
    {
        $entityManager = $this->em;
        $rmEntry = $entityManager->getRepository(TravelEntry::class)->findBy([
            'idTravelAccountability' => $travelAccountability->getId(),
        ]);
        foreach ($rmEntry as $entry) {
            $entityManager->remove($entry);
            $entityManager->flush();
        }

        foreach ($entries as $singleEntry) {
            $this->saveTravelEntries($singleEntry, $travelAccountability);
        }
    }

    public function saveTravelExpenseReport(array $data, TravelAccountability $accountability, ?bool $isCommit = false)
    {
        $entityManager = $this->em;
        $expense = new Expenses();
        $expense->setNome($data['name']);

        $value = (float) $data['value'];
        $expense->setValor($value ?? 0);

        $expense->setIdAccountability($accountability);
        $entityManager->persist($expense);
        //$entityManager->flush();
        if ($isCommit) {
            $entityManager->flush();
        }
    }

    public function saveTravelEntries(array $data, TravelAccountability $accountability, ?bool $isCommit = false)
    {
        $entityManager = $this->em;
        $entry = new TravelEntry();
        $entry->setNome($data['customer']);

        $value = (float) $data['order-value'];
        $entry->setOrderValue($value ?? 0);

        $value = (float) $data['freight'];
        $entry->setFreight($value ?? 0);

        $value = (float) $data['check'];
        $entry->setCheckValue($value ?? 0);

        $value = (float) $data['freight'];
        $entry->setFreight($value ?? 0);

        $value = (float) $data['other'];
        $entry->setByCountValue($value ?? 0);

        $entry->setIdTravelAccountability($accountability);
        
        $entityManager->persist($entry);
        //$entityManager->flush();
        if ($isCommit) {
            $entityManager->flush();
        }
    }
}
