<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\Expenses;

/**
 * Class ExpensesModel
 * @package App\Model
 */
final class ExpensesModel extends Model
{
    /**
     * @param array $expenseInformation
     * @return Expenses
     */
    public function create(array $expenseInformation): Expenses
    {
        try {
            return $this->createExpense($expenseInformation);
        } catch (\PDOException $err) {
            throw new \PDOException($err->getMessage());
        }
    }

    /**
     * @param array $expenseInformation
     * @return Expenses
     */
    private function createExpense(array $expenseInformation): Expenses
    {
        $entityManager = $this->entityManager;
        $expense = new Expenses();
        $expense->setNome($expenseInformation['name']);
        $expense->setValor((float) $expenseInformation['value']);
        $entityManager->persist($expense);
        $entityManager->flush();

        return $expense;
    }

    /**
     * @param int $travelAccountabilityId
     */
    public function removeAllExpensesByAccountabilityId(int $travelAccountabilityId): void
    {
        $dqlStringConsult = sprintf(
            'DELETE FROM %s e WHERE e.idAccountability = %s',
            Expenses::class,
            $travelAccountabilityId
        );
        $this->dqlQuery($dqlStringConsult);
    }
}