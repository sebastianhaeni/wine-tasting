<?php

namespace WineTasting\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use WineTasting\Model\Wine;
use WineTasting\Model\WineQuery;


/**
 * This class defines the structure of the 'wine' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class WineTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'WineTasting.Model.Map.WineTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'wine';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\WineTasting\\Model\\Wine';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'WineTasting.Model.Wine';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the idWine field
     */
    const COL_IDWINE = 'wine.idWine';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'wine.name';

    /**
     * the column name for the picture field
     */
    const COL_PICTURE = 'wine.picture';

    /**
     * the column name for the year field
     */
    const COL_YEAR = 'wine.year';

    /**
     * the column name for the idSubmitter field
     */
    const COL_IDSUBMITTER = 'wine.idSubmitter';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('IdWine', 'Name', 'Picture', 'Year', 'Submitter', ),
        self::TYPE_CAMELNAME     => array('idWine', 'name', 'picture', 'year', 'submitter', ),
        self::TYPE_COLNAME       => array(WineTableMap::COL_IDWINE, WineTableMap::COL_NAME, WineTableMap::COL_PICTURE, WineTableMap::COL_YEAR, WineTableMap::COL_IDSUBMITTER, ),
        self::TYPE_FIELDNAME     => array('idWine', 'name', 'picture', 'year', 'idSubmitter', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('IdWine' => 0, 'Name' => 1, 'Picture' => 2, 'Year' => 3, 'Submitter' => 4, ),
        self::TYPE_CAMELNAME     => array('idWine' => 0, 'name' => 1, 'picture' => 2, 'year' => 3, 'submitter' => 4, ),
        self::TYPE_COLNAME       => array(WineTableMap::COL_IDWINE => 0, WineTableMap::COL_NAME => 1, WineTableMap::COL_PICTURE => 2, WineTableMap::COL_YEAR => 3, WineTableMap::COL_IDSUBMITTER => 4, ),
        self::TYPE_FIELDNAME     => array('idWine' => 0, 'name' => 1, 'picture' => 2, 'year' => 3, 'idSubmitter' => 4, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('wine');
        $this->setPhpName('Wine');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\WineTasting\\Model\\Wine');
        $this->setPackage('WineTasting.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('idWine', 'IdWine', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 200, null);
        $this->addColumn('picture', 'Picture', 'VARCHAR', true, 200, null);
        $this->addColumn('year', 'Year', 'SMALLINT', true, null, null);
        $this->addForeignKey('idSubmitter', 'Submitter', 'INTEGER', 'user', 'idUser', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserRelatedBySubmitter', '\\WineTasting\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':idSubmitter',
    1 => ':idUser',
  ),
), 'CASCADE', 'CASCADE', null, false);
        $this->addRelation('UserRelatedByVote1', '\\WineTasting\\Model\\User', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':vote1',
    1 => ':idWine',
  ),
), 'SET NULL', 'SET NULL', 'UsersRelatedByVote1', false);
        $this->addRelation('UserRelatedByVote2', '\\WineTasting\\Model\\User', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':vote2',
    1 => ':idWine',
  ),
), 'SET NULL', 'SET NULL', 'UsersRelatedByVote2', false);
        $this->addRelation('UserRelatedByVote3', '\\WineTasting\\Model\\User', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':vote3',
    1 => ':idWine',
  ),
), 'SET NULL', 'SET NULL', 'UsersRelatedByVote3', false);
    } // buildRelations()
    /**
     * Method to invalidate the instance pool of all tables related to wine     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        UserTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdWine', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdWine', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('IdWine', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? WineTableMap::CLASS_DEFAULT : WineTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Wine object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = WineTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = WineTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + WineTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = WineTableMap::OM_CLASS;
            /** @var Wine $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            WineTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = WineTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = WineTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Wine $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                WineTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(WineTableMap::COL_IDWINE);
            $criteria->addSelectColumn(WineTableMap::COL_NAME);
            $criteria->addSelectColumn(WineTableMap::COL_PICTURE);
            $criteria->addSelectColumn(WineTableMap::COL_YEAR);
            $criteria->addSelectColumn(WineTableMap::COL_IDSUBMITTER);
        } else {
            $criteria->addSelectColumn($alias . '.idWine');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.picture');
            $criteria->addSelectColumn($alias . '.year');
            $criteria->addSelectColumn($alias . '.idSubmitter');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(WineTableMap::DATABASE_NAME)->getTable(WineTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(WineTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(WineTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new WineTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Wine or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Wine object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WineTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \WineTasting\Model\Wine) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(WineTableMap::DATABASE_NAME);
            $criteria->add(WineTableMap::COL_IDWINE, (array) $values, Criteria::IN);
        }

        $query = WineQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            WineTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                WineTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the wine table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return WineQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Wine or Criteria object.
     *
     * @param mixed               $criteria Criteria or Wine object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WineTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Wine object
        }

        if ($criteria->containsKey(WineTableMap::COL_IDWINE) && $criteria->keyContainsValue(WineTableMap::COL_IDWINE) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.WineTableMap::COL_IDWINE.')');
        }


        // Set the correct dbName
        $query = WineQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // WineTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
WineTableMap::buildTableMap();
