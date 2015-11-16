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
use WineTasting\Model\User as ChildUser;
use WineTasting\Model\UserQuery as ChildUserQuery;
use WineTasting\Model\Map\UserTableMap;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderByIdUser($order = Criteria::ASC) Order by the idUser column
 * @method     ChildUserQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildUserQuery orderByVote1($order = Criteria::ASC) Order by the vote1 column
 * @method     ChildUserQuery orderByVote2($order = Criteria::ASC) Order by the vote2 column
 * @method     ChildUserQuery orderByVote3($order = Criteria::ASC) Order by the vote3 column
 *
 * @method     ChildUserQuery groupByIdUser() Group by the idUser column
 * @method     ChildUserQuery groupByName() Group by the name column
 * @method     ChildUserQuery groupByVote1() Group by the vote1 column
 * @method     ChildUserQuery groupByVote2() Group by the vote2 column
 * @method     ChildUserQuery groupByVote3() Group by the vote3 column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWineRelatedByVote1($relationAlias = null) Adds a LEFT JOIN clause to the query using the WineRelatedByVote1 relation
 * @method     ChildUserQuery rightJoinWineRelatedByVote1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WineRelatedByVote1 relation
 * @method     ChildUserQuery innerJoinWineRelatedByVote1($relationAlias = null) Adds a INNER JOIN clause to the query using the WineRelatedByVote1 relation
 *
 * @method     ChildUserQuery leftJoinWineRelatedByVote2($relationAlias = null) Adds a LEFT JOIN clause to the query using the WineRelatedByVote2 relation
 * @method     ChildUserQuery rightJoinWineRelatedByVote2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WineRelatedByVote2 relation
 * @method     ChildUserQuery innerJoinWineRelatedByVote2($relationAlias = null) Adds a INNER JOIN clause to the query using the WineRelatedByVote2 relation
 *
 * @method     ChildUserQuery leftJoinWineRelatedByVote3($relationAlias = null) Adds a LEFT JOIN clause to the query using the WineRelatedByVote3 relation
 * @method     ChildUserQuery rightJoinWineRelatedByVote3($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WineRelatedByVote3 relation
 * @method     ChildUserQuery innerJoinWineRelatedByVote3($relationAlias = null) Adds a INNER JOIN clause to the query using the WineRelatedByVote3 relation
 *
 * @method     ChildUserQuery leftJoinWineRelatedBySubmitter($relationAlias = null) Adds a LEFT JOIN clause to the query using the WineRelatedBySubmitter relation
 * @method     ChildUserQuery rightJoinWineRelatedBySubmitter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WineRelatedBySubmitter relation
 * @method     ChildUserQuery innerJoinWineRelatedBySubmitter($relationAlias = null) Adds a INNER JOIN clause to the query using the WineRelatedBySubmitter relation
 *
 * @method     \WineTasting\Model\WineQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneByIdUser(int $idUser) Return the first ChildUser filtered by the idUser column
 * @method     ChildUser findOneByName(string $name) Return the first ChildUser filtered by the name column
 * @method     ChildUser findOneByVote1(int $vote1) Return the first ChildUser filtered by the vote1 column
 * @method     ChildUser findOneByVote2(int $vote2) Return the first ChildUser filtered by the vote2 column
 * @method     ChildUser findOneByVote3(int $vote3) Return the first ChildUser filtered by the vote3 column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneByIdUser(int $idUser) Return the first ChildUser filtered by the idUser column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByName(string $name) Return the first ChildUser filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByVote1(int $vote1) Return the first ChildUser filtered by the vote1 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByVote2(int $vote2) Return the first ChildUser filtered by the vote2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByVote3(int $vote3) Return the first ChildUser filtered by the vote3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findByIdUser(int $idUser) Return ChildUser objects filtered by the idUser column
 * @method     ChildUser[]|ObjectCollection findByName(string $name) Return ChildUser objects filtered by the name column
 * @method     ChildUser[]|ObjectCollection findByVote1(int $vote1) Return ChildUser objects filtered by the vote1 column
 * @method     ChildUser[]|ObjectCollection findByVote2(int $vote2) Return ChildUser objects filtered by the vote2 column
 * @method     ChildUser[]|ObjectCollection findByVote3(int $vote3) Return ChildUser objects filtered by the vote3 column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \WineTasting\Model\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\WineTasting\\Model\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT idUser, name, vote1, vote2, vote3 FROM user WHERE idUser = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(12, 56, 832), $con);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_IDUSER, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_IDUSER, $keys, Criteria::IN);
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
     * @param     mixed $idUser The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByIdUser($idUser = null, $comparison = null)
    {
        if (is_array($idUser)) {
            $useMinMax = false;
            if (isset($idUser['min'])) {
                $this->addUsingAlias(UserTableMap::COL_IDUSER, $idUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idUser['max'])) {
                $this->addUsingAlias(UserTableMap::COL_IDUSER, $idUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_IDUSER, $idUser, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the vote1 column
     *
     * Example usage:
     * <code>
     * $query->filterByVote1(1234); // WHERE vote1 = 1234
     * $query->filterByVote1(array(12, 34)); // WHERE vote1 IN (12, 34)
     * $query->filterByVote1(array('min' => 12)); // WHERE vote1 > 12
     * </code>
     *
     * @see       filterByWineRelatedByVote1()
     *
     * @param     mixed $vote1 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByVote1($vote1 = null, $comparison = null)
    {
        if (is_array($vote1)) {
            $useMinMax = false;
            if (isset($vote1['min'])) {
                $this->addUsingAlias(UserTableMap::COL_VOTE1, $vote1['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($vote1['max'])) {
                $this->addUsingAlias(UserTableMap::COL_VOTE1, $vote1['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_VOTE1, $vote1, $comparison);
    }

    /**
     * Filter the query on the vote2 column
     *
     * Example usage:
     * <code>
     * $query->filterByVote2(1234); // WHERE vote2 = 1234
     * $query->filterByVote2(array(12, 34)); // WHERE vote2 IN (12, 34)
     * $query->filterByVote2(array('min' => 12)); // WHERE vote2 > 12
     * </code>
     *
     * @see       filterByWineRelatedByVote2()
     *
     * @param     mixed $vote2 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByVote2($vote2 = null, $comparison = null)
    {
        if (is_array($vote2)) {
            $useMinMax = false;
            if (isset($vote2['min'])) {
                $this->addUsingAlias(UserTableMap::COL_VOTE2, $vote2['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($vote2['max'])) {
                $this->addUsingAlias(UserTableMap::COL_VOTE2, $vote2['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_VOTE2, $vote2, $comparison);
    }

    /**
     * Filter the query on the vote3 column
     *
     * Example usage:
     * <code>
     * $query->filterByVote3(1234); // WHERE vote3 = 1234
     * $query->filterByVote3(array(12, 34)); // WHERE vote3 IN (12, 34)
     * $query->filterByVote3(array('min' => 12)); // WHERE vote3 > 12
     * </code>
     *
     * @see       filterByWineRelatedByVote3()
     *
     * @param     mixed $vote3 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByVote3($vote3 = null, $comparison = null)
    {
        if (is_array($vote3)) {
            $useMinMax = false;
            if (isset($vote3['min'])) {
                $this->addUsingAlias(UserTableMap::COL_VOTE3, $vote3['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($vote3['max'])) {
                $this->addUsingAlias(UserTableMap::COL_VOTE3, $vote3['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_VOTE3, $vote3, $comparison);
    }

    /**
     * Filter the query by a related \WineTasting\Model\Wine object
     *
     * @param \WineTasting\Model\Wine|ObjectCollection $wine The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByWineRelatedByVote1($wine, $comparison = null)
    {
        if ($wine instanceof \WineTasting\Model\Wine) {
            return $this
                ->addUsingAlias(UserTableMap::COL_VOTE1, $wine->getIdWine(), $comparison);
        } elseif ($wine instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTableMap::COL_VOTE1, $wine->toKeyValue('PrimaryKey', 'IdWine'), $comparison);
        } else {
            throw new PropelException('filterByWineRelatedByVote1() only accepts arguments of type \WineTasting\Model\Wine or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WineRelatedByVote1 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinWineRelatedByVote1($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WineRelatedByVote1');

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
            $this->addJoinObject($join, 'WineRelatedByVote1');
        }

        return $this;
    }

    /**
     * Use the WineRelatedByVote1 relation Wine object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\WineQuery A secondary query class using the current class as primary query
     */
    public function useWineRelatedByVote1Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWineRelatedByVote1($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WineRelatedByVote1', '\WineTasting\Model\WineQuery');
    }

    /**
     * Filter the query by a related \WineTasting\Model\Wine object
     *
     * @param \WineTasting\Model\Wine|ObjectCollection $wine The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByWineRelatedByVote2($wine, $comparison = null)
    {
        if ($wine instanceof \WineTasting\Model\Wine) {
            return $this
                ->addUsingAlias(UserTableMap::COL_VOTE2, $wine->getIdWine(), $comparison);
        } elseif ($wine instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTableMap::COL_VOTE2, $wine->toKeyValue('PrimaryKey', 'IdWine'), $comparison);
        } else {
            throw new PropelException('filterByWineRelatedByVote2() only accepts arguments of type \WineTasting\Model\Wine or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WineRelatedByVote2 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinWineRelatedByVote2($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WineRelatedByVote2');

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
            $this->addJoinObject($join, 'WineRelatedByVote2');
        }

        return $this;
    }

    /**
     * Use the WineRelatedByVote2 relation Wine object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\WineQuery A secondary query class using the current class as primary query
     */
    public function useWineRelatedByVote2Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWineRelatedByVote2($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WineRelatedByVote2', '\WineTasting\Model\WineQuery');
    }

    /**
     * Filter the query by a related \WineTasting\Model\Wine object
     *
     * @param \WineTasting\Model\Wine|ObjectCollection $wine The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByWineRelatedByVote3($wine, $comparison = null)
    {
        if ($wine instanceof \WineTasting\Model\Wine) {
            return $this
                ->addUsingAlias(UserTableMap::COL_VOTE3, $wine->getIdWine(), $comparison);
        } elseif ($wine instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserTableMap::COL_VOTE3, $wine->toKeyValue('PrimaryKey', 'IdWine'), $comparison);
        } else {
            throw new PropelException('filterByWineRelatedByVote3() only accepts arguments of type \WineTasting\Model\Wine or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WineRelatedByVote3 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinWineRelatedByVote3($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WineRelatedByVote3');

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
            $this->addJoinObject($join, 'WineRelatedByVote3');
        }

        return $this;
    }

    /**
     * Use the WineRelatedByVote3 relation Wine object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\WineQuery A secondary query class using the current class as primary query
     */
    public function useWineRelatedByVote3Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWineRelatedByVote3($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WineRelatedByVote3', '\WineTasting\Model\WineQuery');
    }

    /**
     * Filter the query by a related \WineTasting\Model\Wine object
     *
     * @param \WineTasting\Model\Wine|ObjectCollection $wine the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByWineRelatedBySubmitter($wine, $comparison = null)
    {
        if ($wine instanceof \WineTasting\Model\Wine) {
            return $this
                ->addUsingAlias(UserTableMap::COL_IDUSER, $wine->getSubmitter(), $comparison);
        } elseif ($wine instanceof ObjectCollection) {
            return $this
                ->useWineRelatedBySubmitterQuery()
                ->filterByPrimaryKeys($wine->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWineRelatedBySubmitter() only accepts arguments of type \WineTasting\Model\Wine or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WineRelatedBySubmitter relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinWineRelatedBySubmitter($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WineRelatedBySubmitter');

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
            $this->addJoinObject($join, 'WineRelatedBySubmitter');
        }

        return $this;
    }

    /**
     * Use the WineRelatedBySubmitter relation Wine object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\WineQuery A secondary query class using the current class as primary query
     */
    public function useWineRelatedBySubmitterQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWineRelatedBySubmitter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WineRelatedBySubmitter', '\WineTasting\Model\WineQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_IDUSER, $user->getIdUser(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserQuery
