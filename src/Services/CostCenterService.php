<?php

namespace muh\accounting\Services;

use muh\accounting\Models\CostCenter;
use yii\db\Exception;
use Yii;

/**
 * Service class for managing cost centers.
 */
class CostCenterService
{
    /**
     * Creates a new cost center.
     *
     * @param string $code        The unique code for the cost center.
     * @param string $name        The name of the cost center.
     * @param string|null $description Optional description.
     *
     * @return CostCenter
     * @throws Exception
     */
    public function createCostCenter(string $code, string $name, ?string $description = null): CostCenter
    {
        $costCenter = new CostCenter();
        $costCenter->code = $code;
        $costCenter->name = $name;
        $costCenter->description = $description;

        if (!$costCenter->save()) {
            throw new Exception('Failed to create cost center: ' . implode(', ', $costCenter->getFirstErrors()));
        }

        return $costCenter;
    }

    /**
     * Updates an existing cost center.
     *
     * @param int $id   The cost center ID.
     * @param array $data Associative array of attributes to update.
     *
     * @return CostCenter
     * @throws Exception
     */
    public function updateCostCenter(int $id, array $data): CostCenter
    {
        $costCenter = CostCenter::findOne($id);
        if (!$costCenter) {
            throw new Exception('Cost center not found.');
        }

        $costCenter->setAttributes($data);
        if (!$costCenter->save()) {
            throw new Exception('Failed to update cost center: ' . implode(', ', $costCenter->getFirstErrors()));
        }

        return $costCenter;
    }

    /**
     * Deletes a cost center.
     *
     * @param int $id The cost center ID.
     *
     * @return bool
     * @throws Exception
     */
    public function deleteCostCenter(int $id): bool
    {
        $costCenter = CostCenter::findOne($id);
        if (!$costCenter) {
            throw new Exception('Cost center not found.');
        }

        if (!$costCenter->delete()) {
            throw new Exception('Failed to delete cost center.');
        }

        return true;
    }

    /**
     * Retrieves all cost centers.
     *
     * @return CostCenter[]
     */
    public function getAllCostCenters(): array
    {
        return CostCenter::find()->orderBy('code')->all();
    }
}
