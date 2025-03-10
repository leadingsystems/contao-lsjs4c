<?php

namespace LeadingSystems\MerconisBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
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
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

            // Prüfen, ob in den relevanten Feldern noch Daten vorhanden sind
            $count = $queryBuilder
                ->select('COUNT(*)')
                ->from('tl_layout')
                ->where('lsjs4c_coreCustomizationToLoadTextPath IS NOT NULL OR lsjs4c_coreCustomizationToLoad != \'\'')
                ->orWhere('lsjs4c_appCustomizationToLoadTextPath IS NOT NULL OR lsjs4c_appCustomizationToLoad != \'\'')
                ->orWhere('lsjs4c_appToLoadTextPath IS NOT NULL OR lsjs4c_appToLoad != \'\'')
                ->executeQuery()
                ->fetchOne();

            return $count > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function run(): MigrationResult
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder();

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
                // Deserialisieren der Array-Felder
                $coreCustomization = unserialize($record['lsjs4c_coreCustomizationToLoad']) ?: [];
                $appCustomization = unserialize($record['lsjs4c_appCustomizationToLoad']) ?: [];
                $appToLoad = unserialize($record['lsjs4c_appToLoad']) ?: [];

                // Addieren der Text-Path-Felder
                if ($record['lsjs4c_coreCustomizationToLoadTextPath']) {
                    $coreCustomization[] = $record['lsjs4c_coreCustomizationToLoadTextPath'];
                }

                if ($record['lsjs4c_appCustomizationToLoadTextPath']) {
                    $appCustomization[] = $record['lsjs4c_appCustomizationToLoadTextPath'];
                }

                if ($record['lsjs4c_appToLoadTextPath']) {
                    $appCustomization[] = $record['lsjs4c_appToLoadTextPath'];
                }

                // Serialisieren der konsolidierten Daten
                $serializedCoreCustomization = serialize($coreCustomization);
                $serializedAppCustomization = serialize(array_merge($appCustomization, $appToLoad));

                // Update der neuen Felder in der Datenbank und Leeren der alten Felder
                $queryBuilder
                    ->update('tl_layout')
                    ->set('lsjs4c_coreCustomization', ':core')
                    ->set('lsjs4c_appCustomization', ':app')
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

            return new MigrationResult(true, 'Data successfully migrated.');
        } catch (Exception $e) {
            return new MigrationResult(false, 'Migration failed: ' . $e->getMessage());
        }
    }
}