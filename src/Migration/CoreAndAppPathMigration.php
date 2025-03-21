<?php

namespace LeadingSystems\LSJS4CBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\System;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class CoreAndAppPathMigration extends AbstractMigration
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        $tableExist = $schemaManager->tablesExist(['tl_layout']);

        // If the table don't exist don't update
        if (!$tableExist) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_layout');

            // Needs to be checked in lowercase because keys are lowercase
            $fieldsExist =
                isset($columns[strtolower('lsjs4c_appCustomizationsToLoad')]) &&
                isset($columns[strtolower('lsjs4c_appsToLoad')]);


        // If the fields don't exist don't update
        if ($fieldsExist) {
            return false;
        }

        $oldFieldsExist =
            isset($columns[strtolower('lsjs4c_coreCustomizationToLoadTextPath')]) &&
            isset($columns[strtolower('lsjs4c_coreCustomizationToLoad')]) &&
            isset($columns[strtolower('lsjs4c_appCustomizationToLoadTextPath')]) &&
            isset($columns[strtolower('lsjs4c_appToLoadTextPath')]) &&
            isset($columns[strtolower('lsjs4c_appToLoad')]);

        if (!$oldFieldsExist) {
            return false;
        }

        $queryBuilder = $this->connection->createQueryBuilder();

        // Check whether there is still data in the relevant fields
        $count = $queryBuilder
            ->select('COUNT(*)')
            ->from('tl_layout')
            ->where('lsjs4c_coreCustomizationToLoadTextPath IS NOT NULL OR lsjs4c_coreCustomizationToLoad != \'\'')
            ->orWhere('lsjs4c_appCustomizationToLoadTextPath IS NOT NULL OR lsjs4c_appCustomizationToLoad != \'\'')
            ->orWhere('lsjs4c_appToLoadTextPath IS NOT NULL OR lsjs4c_appToLoad != \'\'')
            ->executeQuery()
            ->fetchOne();

        return $count > 0;
    }

    public function run(): MigrationResult
    {

        $queryBuilder = $this->connection->createQueryBuilder();

        // @toDo creat new DCA Field appsToLoad and coreCustomizations


        // Fetch all records from the table
        $records = $queryBuilder
            ->select('id',
                'lsjs4c_coreCustomizationToLoadTextPath',
                'lsjs4c_coreCustomizationToLoad',
                'lsjs4c_appCustomizationToLoadTextPath',
                'lsjs4c_appCustomizationToLoad',
                'lsjs4c_appToLoadTextPath',
                'lsjs4c_appToLoad')
            ->from('tl_layout')
            ->executeQuery()
            ->fetchAllAssociative();


        foreach ($records as $record) {

            $coreCustomization = $this->convertFileIdToPath($record['lsjs4c_coreCustomizationToLoad']) ?: [];
            $appCustomization = $this->convertFileIdToPath($record['lsjs4c_appCustomizationToLoad']) ?: [];
            $appToLoad = $this->convertFileIdToPath($record['lsjs4c_appToLoad']) ?: [];

            // Adding the text path fields
            if ($record['lsjs4c_coreCustomizationToLoadTextPath']) {
                $coreCustomization[] = $record['lsjs4c_coreCustomizationToLoadTextPath'];
            }

            if ($record['lsjs4c_appCustomizationToLoadTextPath']) {
                $appCustomization[] = $record['lsjs4c_appCustomizationToLoadTextPath'];
            }

            if ($record['lsjs4c_appToLoadTextPath']) {
                $appCustomization[] = $record['lsjs4c_appToLoadTextPath'];
            }


            $serializedCoreCustomization = serialize($coreCustomization);
            $serializedAppCustomization = serialize(array_merge($appToLoad, $appCustomization));

            // Update the new fields in the database and empty the old fields
            $queryBuilder
                ->update('tl_layout')
                ->set('lsjs4c_appCustomizationsToLoad', ':core')
                ->set('lsjs4c_appsToLoad', ':app')
                ->set('lsjs4c_coreCustomizationToLoadTextPath', 'NULL')
                ->set('lsjs4c_coreCustomizationToLoad', 'NULL')
                ->set('lsjs4c_appCustomizationToLoadTextPath', 'NULL')
                ->set('lsjs4c_appCustomizationToLoad', 'NULL')
                ->set('lsjs4c_appToLoadTextPath', 'NULL')
                ->set('lsjs4c_appToLoad', 'NULL')
                ->where('id = :id')
                ->setParameter('core', $serializedCoreCustomization)
                ->setParameter('app', $serializedAppCustomization)
                ->setParameter('id', $record['id'])
                ->executeStatement();
        }

        return $this->createResult(true);

    }


    private function convertFileIdToPath($id)
    {
        $file = FilesModel::findById($id);
        if ($file) {
            return ['files/' . preg_replace('(^' . preg_quote(System::getContainer()->getParameter('contao.upload_path')) . '/)', '', $file->path)];
        }
        return [];
    }
}