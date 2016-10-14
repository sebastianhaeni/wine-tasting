<?php

namespace WineTasting\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use WineTasting\Model\TastedWine as ChildTastedWine;
use WineTasting\Model\TastedWineQuery as ChildTastedWineQuery;
use WineTasting\Model\Map\TastedWineTableMap;

/**
 * Base class that represents a query for the 'tasted_wine' table.
 *
 *
 *
 * @method     ChildTastedWineQuery orderByIdUser($order = Criteria::ASC) Order by the idUser column
 * @method     ChildTastedWineQuery orderByIdWine($order = Criteria::ASC) Order by the idWine column
 *
 * @method     ChildTastedWineQuery groupByIdUser() Group by the idUser column
 * @method     ChildTastedWineQuery groupByIdWine() Group by the idWine column
 *
 * @method     ChildTastedWineQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTastedWineQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTastedWineQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTastedWineQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildTastedWineQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildTastedWineQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildTastedWineQuery leftJoinWine($relationAlias = null) Adds a LEFT JOIN clause to the query using the Wine relation
 * @method     ChildTastedWineQuery rightJoinWine($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Wine relation
 * @method     ChildTastedWineQuery innerJoinWine($relationAlias = null) Adds a INNER JOIN clause to the query using the Wine relation
 *
 * @method     \WineTasting\Model\UserQuery|\WineTasting\Model\WineQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTastedWine findOne(ConnectionInterface $con = null) Return the first ChildTastedWine matching the query
 * @method     ChildTastedWine findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTastedWine matching the query, or a new ChildTastedWine object populated from the query conditions when no match is found
 *
 * @method     ChildTastedWine findOneByIdUser(int $idUser) Return the first ChildTastedWine filtered by the idUser column
 * @method     ChildTastedWine findOneByIdWine(int $idWine) Return the first ChildTastedWine filtered by the idWine column *

 * @method     ChildTastedWine requirePk($key, ConnectionInterface $con = null) Return the ChildTastedWine by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTastedWine requireOne(ConnectionInterface $con = null) Return the first ChildTastedWine matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTastedWine requireOneByIdUser(int $idUser) Return the first ChildTastedWine filtered by the idUser column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTastedWine requireOneByIdWine(int $idWine) Return the first ChildTastedWine filtered by the idWine column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTastedWine[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTastedWine objects based on current ModelCriteria
 * @method     ChildTastedWine[]|ObjectCollection findByIdUser(int $idUser) Return ChildTastedWine objects filtered by the idUser column
 * @method     ChildTastedWine[]|ObjectCollection findByIdWine(int $idWine) Return ChildTastedWine objects filtered by the idWine column
 * @method     ChildTastedWine[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TastedWineQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \WineTasting\Model\Base\TastedWineQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\WineTasting\\Model\\TastedWine', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTastedWineQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTastedWineQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTastedWineQuery) {
            return $criteria;
        }
        $query = new ChildTastedWineQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$idUser, $idWine] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildTastedWine|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TastedWineTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TastedWineTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTastedWine A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT idUser, idWine FROM tasted_wine WHERE idUser = :p0 AND idWine = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildTastedWine $obj */
            $obj = new ChildTastedWine();
            $obj->hydrate($row);
            TastedWineTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildTastedWine|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildTastedWineQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(TastedWineTableMap::COL_IDUSER, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(TastedWineTableMap::COL_IDWINE, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTastedWineQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(TastedWineTableMap::COL_IDUSER, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(TastedWineTableMap::COL_IDWINE, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the idUser column
     *
     * Example usage:
     * <code>
     * $query->filterByIdUser(1234); // WHERE idUser = 1234
     * $query->filterByIdUser(array(12, 34)); // WHERE idUser IN (12, 34)
     * $query->filterByIdUser(array('min' => 12)); // WHERE idUser > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $idUser The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTastedWineQuery The current query, for fluid interface
     */
    public function filterByIdUser($idUser = null, $comparison = null)
    {
        if (is_array($idUser)) {
            $useMinMax = false;
            if (isset($idUser['min'])) {
                $this->addUsingAlias(TastedWineTableMap::COL_IDUSER, $idUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idUser['max'])) {
                $this->addUsingAlias(TastedWineTableMap::COL_IDUSER, $idUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TastedWineTableMap::COL_IDUSER, $idUser, $comparison);
    }

    /**
     * Filter the query on the idWine column
     *
     * Example usage:
     * <code>
     * $query->filterByIdWine(1234); // WHERE idWine = 1234
     * $query->filterByIdWine(array(12, 34)); // WHERE idWine IN (12, 34)
     * $query->filterByIdWine(array('min' => 12)); // WHERE idWine > 12
     * </code>
     *
     * @see       filterByWine()
     *
     * @param     mixed $idWine The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTastedWineQuery The current query, for fluid interface
     */
    public function filterByIdWine($idWine = null, $comparison = null)
    {
        if (is_array($idWine)) {
            $useMinMax = false;
            if (isset($idWine['min'])) {
                $this->addUsingAlias(TastedWineTableMap::COL_IDWINE, $idWine['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idWine['max'])) {
                $this->addUsingAlias(TastedWineTableMap::COL_IDWINE, $idWine['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TastedWineTableMap::COL_IDWINE, $idWine, $comparison);
    }

    /**
     * Filter the query by a related \WineTasting\Model\User object
     *
     * @param \WineTasting\Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTastedWineQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \WineTasting\Model\User) {
            return $this
                ->addUsingAlias(TastedWineTableMap::COL_IDUSER, $user->getIdUser(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TastedWineTableMap::COL_IDUSER, $user->toKeyValue('PrimaryKey', 'IdUser'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \WineTasting\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTastedWineQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\WineTasting\Model\UserQuery');
    }

    /**
     * Filter the query by a related \WineTasting\Model\Wine object
     *
     * @param \WineTasting\Model\Wine|ObjectCollection $wine The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTastedWineQuery The current query, for fluid interface
     */
    public function filterByWine($wine, $comparison = null)
    {
        if ($wine instanceof \WineTasting\Model\Wine) {
            return $this
                ->addUsingAlias(TastedWineTableMap::COL_IDWINE, $wine->getIdWine(), $comparison);
        } elseif ($wine instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TastedWineTableMap::COL_IDWINE, $wine->toKeyValue('PrimaryKey', 'IdWine'), $comparison);
        } else {
            throw new PropelException('filterByWine() only accepts arguments of type \WineTasting\Model\Wine or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Wine relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTastedWineQuery The current query, for fluid interface
     */
    public function joinWine($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Wine');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Wine');
        }

        return $this;
    }

    /**
     * Use the Wine relation Wine object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\WineQuery A secondary query class using the current class as primary query
     */
    public function useWineQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWine($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Wine', '\WineTasting\Model\WineQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTastedWine $tastedWine Object to remove from the list of results
     *
     * @return $this|ChildTastedWineQuery The current query, for fluid interface
     */
    public function prune($tastedWine = null)
    {
        if ($tastedWine) {
            $this->addCond('pruneCond0', $this->getAliasedColName(TastedWineTableMap::COL_IDUSER), $tastedWine->getIdUser(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(TastedWineTableMap::COL_IDWINE), $tastedWine->getIdWine(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the tasted_wine table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TastedWineTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TastedWineTableMap::clearInstancePool();
            TastedWineTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TastedWineTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TastedWineTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TastedWineTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TastedWineTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TastedWineQuery
