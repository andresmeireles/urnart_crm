<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use App\Utils\Andresmei\FlashResponse;
use App\Entity\TravelAccountability;
use App\Utils\Andresmei\NestedArraySeparator;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\Expenses;
use App\Entity\TravelEntry;

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
                throw new \Exception(sprintf('Relaptrio %s não existe', $formName));
        }
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
            $accountability->setDepartureDate(
                new \DateTime($accountabilityData['dt-out'], new \DateTimeZone('America/Sao_Paulo'))
            );
            $accountability->setArrivalDate(
                new \DateTime($accountabilityData['dt-in'], new \DateTimeZone('America/Sao_Paulo'))
            );
            foreach ($entries as $value) {
                $this->saveTravelEntries($value, $accountability);
            }
            
            foreach ($expenses as $value) {
                if ($value['name'] === '') {
                    continue;
                }
                $this->saveTravelExpenseReport($value, $accountability);
            }
            $entityManager->persist($accountability);
            $entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return new FlashResponse(200, 'success', 'Relatorio criado com suceeso.');
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
