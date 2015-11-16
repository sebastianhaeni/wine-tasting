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
use WineTasting\Model\Wine as ChildWine;
use WineTasting\Model\WineQuery as ChildWineQuery;
use WineTasting\Model\Map\WineTableMap;

/**
 * Base class that represents a query for the 'wine' table.
 *
 *
 *
 * @method     ChildWineQuery orderByIdWine($order = Criteria::ASC) Order by the idWine column
 * @method     ChildWineQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildWineQuery orderByPicture($order = Criteria::ASC) Order by the picture column
 * @method     ChildWineQuery orderByYear($order = Criteria::ASC) Order by the year column
 * @method     ChildWineQuery orderBySubmitter($order = Criteria::ASC) Order by the idSubmitter column
 *
 * @method     ChildWineQuery groupByIdWine() Group by the idWine column
 * @method     ChildWineQuery groupByName() Group by the name column
 * @method     ChildWineQuery groupByPicture() Group by the picture column
 * @method     ChildWineQuery groupByYear() Group by the year column
 * @method     ChildWineQuery groupBySubmitter() Group by the idSubmitter column
 *
 * @method     ChildWineQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildWineQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildWineQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildWineQuery leftJoinUserRelatedBySubmitter($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedBySubmitter relation
 * @method     ChildWineQuery rightJoinUserRelatedBySubmitter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedBySubmitter relation
 * @method     ChildWineQuery innerJoinUserRelatedBySubmitter($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedBySubmitter relation
 *
 * @method     ChildWineQuery leftJoinUserRelatedByVote1($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByVote1 relation
 * @method     ChildWineQuery rightJoinUserRelatedByVote1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByVote1 relation
 * @method     ChildWineQuery innerJoinUserRelatedByVote1($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByVote1 relation
 *
 * @method     ChildWineQuery leftJoinUserRelatedByVote2($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByVote2 relation
 * @method     ChildWineQuery rightJoinUserRelatedByVote2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByVote2 relation
 * @method     ChildWineQuery innerJoinUserRelatedByVote2($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByVote2 relation
 *
 * @method     ChildWineQuery leftJoinUserRelatedByVote3($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByVote3 relation
 * @method     ChildWineQuery rightJoinUserRelatedByVote3($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByVote3 relation
 * @method     ChildWineQuery innerJoinUserRelatedByVote3($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByVote3 relation
 *
 * @method     \WineTasting\Model\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildWine findOne(ConnectionInterface $con = null) Return the first ChildWine matching the query
 * @method     ChildWine findOneOrCreate(ConnectionInterface $con = null) Return the first ChildWine matching the query, or a new ChildWine object populated from the query conditions when no match is found
 *
 * @method     ChildWine findOneByIdWine(int $idWine) Return the first ChildWine filtered by the idWine column
 * @method     ChildWine findOneByName(string $name) Return the first ChildWine filtered by the name column
 * @method     ChildWine findOneByPicture(string $picture) Return the first ChildWine filtered by the picture column
 * @method     ChildWine findOneByYear(int $year) Return the first ChildWine filtered by the year column
 * @method     ChildWine findOneBySubmitter(int $idSubmitter) Return the first ChildWine filtered by the idSubmitter column *

 * @method     ChildWine requirePk($key, ConnectionInterface $con = null) Return the ChildWine by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWine requireOne(ConnectionInterface $con = null) Return the first ChildWine matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWine requireOneByIdWine(int $idWine) Return the first ChildWine filtered by the idWine column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWine requireOneByName(string $name) Return the first ChildWine filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWine requireOneByPicture(string $picture) Return the first ChildWine filtered by the picture column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWine requireOneByYear(int $year) Return the first ChildWine filtered by the year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWine requireOneBySubmitter(int $idSubmitter) Return the first ChildWine filtered by the idSubmitter column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWine[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildWine objects based on current ModelCriteria
 * @method     ChildWine[]|ObjectCollection findByIdWine(int $idWine) Return ChildWine objects filtered by the idWine column
 * @method     ChildWine[]|ObjectCollection findByName(string $name) Return ChildWine objects filtered by the name column
 * @method     ChildWine[]|ObjectCollection findByPicture(string $picture) Return ChildWine objects filtered by the picture column
 * @method     ChildWine[]|ObjectCollection findByYear(int $year) Return ChildWine objects filtered by the year column
 * @method     ChildWine[]|ObjectCollection findBySubmitter(int $idSubmitter) Return ChildWine objects filtered by the idSubmitter column
 * @method     ChildWine[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class WineQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \WineTasting\Model\Base\WineQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\WineTasting\\Model\\Wine', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildWineQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildWineQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildWineQuery) {
            return $criteria;
        }
        $query = new ChildWineQuery();
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
     * @return ChildWine|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = WineTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WineTableMap::DATABASE_NAME);
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
     * @return ChildWine A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT idWine, name, picture, year, idSubmitter FROM wine WHERE idWine = :p0';
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
            /** @var ChildWine $obj */
            $obj = new ChildWine();
            $obj->hydrate($row);
            WineTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildWine|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WineTableMap::COL_IDWINE, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WineTableMap::COL_IDWINE, $keys, Criteria::IN);
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
     * @param     mixed $idWine The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function filterByIdWine($idWine = null, $comparison = null)
    {
        if (is_array($idWine)) {
            $useMinMax = false;
            if (isset($idWine['min'])) {
                $this->addUsingAlias(WineTableMap::COL_IDWINE, $idWine['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($idWine['max'])) {
                $this->addUsingAlias(WineTableMap::COL_IDWINE, $idWine['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WineTableMap::COL_IDWINE, $idWine, $comparison);
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
     * @return $this|ChildWineQuery The current query, for fluid interface
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

        return $this->addUsingAlias(WineTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the picture column
     *
     * Example usage:
     * <code>
     * $query->filterByPicture('fooValue');   // WHERE picture = 'fooValue'
     * $query->filterByPicture('%fooValue%'); // WHERE picture LIKE '%fooValue%'
     * </code>
     *
     * @param     string $picture The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function filterByPicture($picture = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($picture)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $picture)) {
                $picture = str_replace('*', '%', $picture);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(WineTableMap::COL_PICTURE, $picture, $comparison);
    }

    /**
     * Filter the query on the year column
     *
     * Example usage:
     * <code>
     * $query->filterByYear(1234); // WHERE year = 1234
     * $query->filterByYear(array(12, 34)); // WHERE year IN (12, 34)
     * $query->filterByYear(array('min' => 12)); // WHERE year > 12
     * </code>
     *
     * @param     mixed $year The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function filterByYear($year = null, $comparison = null)
    {
        if (is_array($year)) {
            $useMinMax = false;
            if (isset($year['min'])) {
                $this->addUsingAlias(WineTableMap::COL_YEAR, $year['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($year['max'])) {
                $this->addUsingAlias(WineTableMap::COL_YEAR, $year['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WineTableMap::COL_YEAR, $year, $comparison);
    }

    /**
     * Filter the query on the idSubmitter column
     *
     * Example usage:
     * <code>
     * $query->filterBySubmitter(1234); // WHERE idSubmitter = 1234
     * $query->filterBySubmitter(array(12, 34)); // WHERE idSubmitter IN (12, 34)
     * $query->filterBySubmitter(array('min' => 12)); // WHERE idSubmitter > 12
     * </code>
     *
     * @see       filterByUserRelatedBySubmitter()
     *
     * @param     mixed $submitter The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function filterBySubmitter($submitter = null, $comparison = null)
    {
        if (is_array($submitter)) {
            $useMinMax = false;
            if (isset($submitter['min'])) {
                $this->addUsingAlias(WineTableMap::COL_IDSUBMITTER, $submitter['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($submitter['max'])) {
                $this->addUsingAlias(WineTableMap::COL_IDSUBMITTER, $submitter['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WineTableMap::COL_IDSUBMITTER, $submitter, $comparison);
    }

    /**
     * Filter the query by a related \WineTasting\Model\User object
     *
     * @param \WineTasting\Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildWineQuery The current query, for fluid interface
     */
    public function filterByUserRelatedBySubmitter($user, $comparison = null)
    {
        if ($user instanceof \WineTasting\Model\User) {
            return $this
                ->addUsingAlias(WineTableMap::COL_IDSUBMITTER, $user->getIdUser(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(WineTableMap::COL_IDSUBMITTER, $user->toKeyValue('PrimaryKey', 'IdUser'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedBySubmitter() only accepts arguments of type \WineTasting\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedBySubmitter relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function joinUserRelatedBySubmitter($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedBySubmitter');

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
            $this->addJoinObject($join, 'UserRelatedBySubmitter');
        }

        return $this;
    }

    /**
     * Use the UserRelatedBySubmitter relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedBySubmitterQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRelatedBySubmitter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedBySubmitter', '\WineTasting\Model\UserQuery');
    }

    /**
     * Filter the query by a related \WineTasting\Model\User object
     *
     * @param \WineTasting\Model\User|ObjectCollection $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildWineQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByVote1($user, $comparison = null)
    {
        if ($user instanceof \WineTasting\Model\User) {
            return $this
                ->addUsingAlias(WineTableMap::COL_IDWINE, $user->getVote1(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useUserRelatedByVote1Query()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserRelatedByVote1() only accepts arguments of type \WineTasting\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByVote1 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function joinUserRelatedByVote1($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByVote1');

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
            $this->addJoinObject($join, 'UserRelatedByVote1');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByVote1 relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByVote1Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByVote1($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByVote1', '\WineTasting\Model\UserQuery');
    }

    /**
     * Filter the query by a related \WineTasting\Model\User object
     *
     * @param \WineTasting\Model\User|ObjectCollection $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildWineQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByVote2($user, $comparison = null)
    {
        if ($user instanceof \WineTasting\Model\User) {
            return $this
                ->addUsingAlias(WineTableMap::COL_IDWINE, $user->getVote2(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useUserRelatedByVote2Query()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserRelatedByVote2() only accepts arguments of type \WineTasting\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByVote2 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function joinUserRelatedByVote2($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByVote2');

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
            $this->addJoinObject($join, 'UserRelatedByVote2');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByVote2 relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByVote2Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByVote2($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByVote2', '\WineTasting\Model\UserQuery');
    }

    /**
     * Filter the query by a related \WineTasting\Model\User object
     *
     * @param \WineTasting\Model\User|ObjectCollection $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildWineQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByVote3($user, $comparison = null)
    {
        if ($user instanceof \WineTasting\Model\User) {
            return $this
                ->addUsingAlias(WineTableMap::COL_IDWINE, $user->getVote3(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useUserRelatedByVote3Query()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserRelatedByVote3() only accepts arguments of type \WineTasting\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByVote3 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function joinUserRelatedByVote3($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByVote3');

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
            $this->addJoinObject($join, 'UserRelatedByVote3');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByVote3 relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \WineTasting\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByVote3Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserRelatedByVote3($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByVote3', '\WineTasting\Model\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildWine $wine Object to remove from the list of results
     *
     * @return $this|ChildWineQuery The current query, for fluid interface
     */
    public function prune($wine = null)
    {
        if ($wine) {
            $this->addUsingAlias(WineTableMap::COL_IDWINE, $wine->getIdWine(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the wine table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WineTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WineTableMap::clearInstancePool();
            WineTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(WineTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(WineTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            WineTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            WineTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // WineQuery
