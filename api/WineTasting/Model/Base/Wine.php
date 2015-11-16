<?php

namespace WineTasting\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use WineTasting\Model\User as ChildUser;
use WineTasting\Model\UserQuery as ChildUserQuery;
use WineTasting\Model\Wine as ChildWine;
use WineTasting\Model\WineQuery as ChildWineQuery;
use WineTasting\Model\Map\WineTableMap;

/**
 * Base class that represents a row from the 'wine' table.
 *
 *
 *
* @package    propel.generator.WineTasting.Model.Base
*/
abstract class Wine implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\WineTasting\\Model\\Map\\WineTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the idwine field.
     * @var        int
     */
    protected $idwine;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the picture field.
     * @var        string
     */
    protected $picture;

    /**
     * The value for the year field.
     * @var        int
     */
    protected $year;

    /**
     * The value for the idsubmitter field.
     * @var        int
     */
    protected $idsubmitter;

    /**
     * @var        ChildUser
     */
    protected $aUserRelatedBySubmitter;

    /**
     * @var        ObjectCollection|ChildUser[] Collection to store aggregation of ChildUser objects.
     */
    protected $collUsersRelatedByVote1;
    protected $collUsersRelatedByVote1Partial;

    /**
     * @var        ObjectCollection|ChildUser[] Collection to store aggregation of ChildUser objects.
     */
    protected $collUsersRelatedByVote2;
    protected $collUsersRelatedByVote2Partial;

    /**
     * @var        ObjectCollection|ChildUser[] Collection to store aggregation of ChildUser objects.
     */
    protected $collUsersRelatedByVote3;
    protected $collUsersRelatedByVote3Partial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersRelatedByVote1ScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersRelatedByVote2ScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersRelatedByVote3ScheduledForDeletion = null;

    /**
     * Initializes internal state of WineTasting\Model\Base\Wine object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Wine</code> instance.  If
     * <code>obj</code> is an instance of <code>Wine</code>, delegates to
     * <code>equals(Wine)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Wine The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [idwine] column value.
     *
     * @return int
     */
    public function getIdWine()
    {
        return $this->idwine;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [picture] column value.
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Get the [year] column value.
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Get the [idsubmitter] column value.
     *
     * @return int
     */
    public function getSubmitter()
    {
        return $this->idsubmitter;
    }

    /**
     * Set the value of [idwine] column.
     *
     * @param int $v new value
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     */
    public function setIdWine($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->idwine !== $v) {
            $this->idwine = $v;
            $this->modifiedColumns[WineTableMap::COL_IDWINE] = true;
        }

        return $this;
    } // setIdWine()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[WineTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [picture] column.
     *
     * @param string $v new value
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     */
    public function setPicture($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->picture !== $v) {
            $this->picture = $v;
            $this->modifiedColumns[WineTableMap::COL_PICTURE] = true;
        }

        return $this;
    } // setPicture()

    /**
     * Set the value of [year] column.
     *
     * @param int $v new value
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     */
    public function setYear($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->year !== $v) {
            $this->year = $v;
            $this->modifiedColumns[WineTableMap::COL_YEAR] = true;
        }

        return $this;
    } // setYear()

    /**
     * Set the value of [idsubmitter] column.
     *
     * @param int $v new value
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     */
    public function setSubmitter($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->idsubmitter !== $v) {
            $this->idsubmitter = $v;
            $this->modifiedColumns[WineTableMap::COL_IDSUBMITTER] = true;
        }

        if ($this->aUserRelatedBySubmitter !== null && $this->aUserRelatedBySubmitter->getIdUser() !== $v) {
            $this->aUserRelatedBySubmitter = null;
        }

        return $this;
    } // setSubmitter()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : WineTableMap::translateFieldName('IdWine', TableMap::TYPE_PHPNAME, $indexType)];
            $this->idwine = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : WineTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : WineTableMap::translateFieldName('Picture', TableMap::TYPE_PHPNAME, $indexType)];
            $this->picture = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : WineTableMap::translateFieldName('Year', TableMap::TYPE_PHPNAME, $indexType)];
            $this->year = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : WineTableMap::translateFieldName('Submitter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->idsubmitter = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = WineTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\WineTasting\\Model\\Wine'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aUserRelatedBySubmitter !== null && $this->idsubmitter !== $this->aUserRelatedBySubmitter->getIdUser()) {
            $this->aUserRelatedBySubmitter = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WineTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildWineQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUserRelatedBySubmitter = null;
            $this->collUsersRelatedByVote1 = null;

            $this->collUsersRelatedByVote2 = null;

            $this->collUsersRelatedByVote3 = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Wine::setDeleted()
     * @see Wine::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(WineTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildWineQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(WineTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                WineTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUserRelatedBySubmitter !== null) {
                if ($this->aUserRelatedBySubmitter->isModified() || $this->aUserRelatedBySubmitter->isNew()) {
                    $affectedRows += $this->aUserRelatedBySubmitter->save($con);
                }
                $this->setUserRelatedBySubmitter($this->aUserRelatedBySubmitter);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->usersRelatedByVote1ScheduledForDeletion !== null) {
                if (!$this->usersRelatedByVote1ScheduledForDeletion->isEmpty()) {
                    foreach ($this->usersRelatedByVote1ScheduledForDeletion as $userRelatedByVote1) {
                        // need to save related object because we set the relation to null
                        $userRelatedByVote1->save($con);
                    }
                    $this->usersRelatedByVote1ScheduledForDeletion = null;
                }
            }

            if ($this->collUsersRelatedByVote1 !== null) {
                foreach ($this->collUsersRelatedByVote1 as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->usersRelatedByVote2ScheduledForDeletion !== null) {
                if (!$this->usersRelatedByVote2ScheduledForDeletion->isEmpty()) {
                    foreach ($this->usersRelatedByVote2ScheduledForDeletion as $userRelatedByVote2) {
                        // need to save related object because we set the relation to null
                        $userRelatedByVote2->save($con);
                    }
                    $this->usersRelatedByVote2ScheduledForDeletion = null;
                }
            }

            if ($this->collUsersRelatedByVote2 !== null) {
                foreach ($this->collUsersRelatedByVote2 as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->usersRelatedByVote3ScheduledForDeletion !== null) {
                if (!$this->usersRelatedByVote3ScheduledForDeletion->isEmpty()) {
                    foreach ($this->usersRelatedByVote3ScheduledForDeletion as $userRelatedByVote3) {
                        // need to save related object because we set the relation to null
                        $userRelatedByVote3->save($con);
                    }
                    $this->usersRelatedByVote3ScheduledForDeletion = null;
                }
            }

            if ($this->collUsersRelatedByVote3 !== null) {
                foreach ($this->collUsersRelatedByVote3 as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[WineTableMap::COL_IDWINE] = true;
        if (null !== $this->idwine) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . WineTableMap::COL_IDWINE . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(WineTableMap::COL_IDWINE)) {
            $modifiedColumns[':p' . $index++]  = 'idWine';
        }
        if ($this->isColumnModified(WineTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(WineTableMap::COL_PICTURE)) {
            $modifiedColumns[':p' . $index++]  = 'picture';
        }
        if ($this->isColumnModified(WineTableMap::COL_YEAR)) {
            $modifiedColumns[':p' . $index++]  = 'year';
        }
        if ($this->isColumnModified(WineTableMap::COL_IDSUBMITTER)) {
            $modifiedColumns[':p' . $index++]  = 'idSubmitter';
        }

        $sql = sprintf(
            'INSERT INTO wine (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'idWine':
                        $stmt->bindValue($identifier, $this->idwine, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'picture':
                        $stmt->bindValue($identifier, $this->picture, PDO::PARAM_STR);
                        break;
                    case 'year':
                        $stmt->bindValue($identifier, $this->year, PDO::PARAM_INT);
                        break;
                    case 'idSubmitter':
                        $stmt->bindValue($identifier, $this->idsubmitter, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setIdWine($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = WineTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getIdWine();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getPicture();
                break;
            case 3:
                return $this->getYear();
                break;
            case 4:
                return $this->getSubmitter();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Wine'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Wine'][$this->hashCode()] = true;
        $keys = WineTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getIdWine(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getPicture(),
            $keys[3] => $this->getYear(),
            $keys[4] => $this->getSubmitter(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUserRelatedBySubmitter) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUserRelatedBySubmitter->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUsersRelatedByVote1) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'Users';
                }

                $result[$key] = $this->collUsersRelatedByVote1->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUsersRelatedByVote2) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'Users';
                }

                $result[$key] = $this->collUsersRelatedByVote2->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUsersRelatedByVote3) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'Users';
                }

                $result[$key] = $this->collUsersRelatedByVote3->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\WineTasting\Model\Wine
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = WineTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\WineTasting\Model\Wine
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setIdWine($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setPicture($value);
                break;
            case 3:
                $this->setYear($value);
                break;
            case 4:
                $this->setSubmitter($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = WineTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setIdWine($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPicture($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setYear($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setSubmitter($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\WineTasting\Model\Wine The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(WineTableMap::DATABASE_NAME);

        if ($this->isColumnModified(WineTableMap::COL_IDWINE)) {
            $criteria->add(WineTableMap::COL_IDWINE, $this->idwine);
        }
        if ($this->isColumnModified(WineTableMap::COL_NAME)) {
            $criteria->add(WineTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(WineTableMap::COL_PICTURE)) {
            $criteria->add(WineTableMap::COL_PICTURE, $this->picture);
        }
        if ($this->isColumnModified(WineTableMap::COL_YEAR)) {
            $criteria->add(WineTableMap::COL_YEAR, $this->year);
        }
        if ($this->isColumnModified(WineTableMap::COL_IDSUBMITTER)) {
            $criteria->add(WineTableMap::COL_IDSUBMITTER, $this->idsubmitter);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildWineQuery::create();
        $criteria->add(WineTableMap::COL_IDWINE, $this->idwine);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getIdWine();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getIdWine();
    }

    /**
     * Generic method to set the primary key (idwine column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setIdWine($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getIdWine();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \WineTasting\Model\Wine (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setPicture($this->getPicture());
        $copyObj->setYear($this->getYear());
        $copyObj->setSubmitter($this->getSubmitter());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUsersRelatedByVote1() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRelatedByVote1($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUsersRelatedByVote2() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRelatedByVote2($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUsersRelatedByVote3() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserRelatedByVote3($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setIdWine(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \WineTasting\Model\Wine Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserRelatedBySubmitter(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setSubmitter(NULL);
        } else {
            $this->setSubmitter($v->getIdUser());
        }

        $this->aUserRelatedBySubmitter = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addWineRelatedBySubmitter($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUserRelatedBySubmitter(ConnectionInterface $con = null)
    {
        if ($this->aUserRelatedBySubmitter === null && ($this->idsubmitter !== null)) {
            $this->aUserRelatedBySubmitter = ChildUserQuery::create()->findPk($this->idsubmitter, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserRelatedBySubmitter->addWinesRelatedBySubmitter($this);
             */
        }

        return $this->aUserRelatedBySubmitter;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('UserRelatedByVote1' == $relationName) {
            return $this->initUsersRelatedByVote1();
        }
        if ('UserRelatedByVote2' == $relationName) {
            return $this->initUsersRelatedByVote2();
        }
        if ('UserRelatedByVote3' == $relationName) {
            return $this->initUsersRelatedByVote3();
        }
    }

    /**
     * Clears out the collUsersRelatedByVote1 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsersRelatedByVote1()
     */
    public function clearUsersRelatedByVote1()
    {
        $this->collUsersRelatedByVote1 = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsersRelatedByVote1 collection loaded partially.
     */
    public function resetPartialUsersRelatedByVote1($v = true)
    {
        $this->collUsersRelatedByVote1Partial = $v;
    }

    /**
     * Initializes the collUsersRelatedByVote1 collection.
     *
     * By default this just sets the collUsersRelatedByVote1 collection to an empty array (like clearcollUsersRelatedByVote1());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsersRelatedByVote1($overrideExisting = true)
    {
        if (null !== $this->collUsersRelatedByVote1 && !$overrideExisting) {
            return;
        }
        $this->collUsersRelatedByVote1 = new ObjectCollection();
        $this->collUsersRelatedByVote1->setModel('\WineTasting\Model\User');
    }

    /**
     * Gets an array of ChildUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildWine is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     * @throws PropelException
     */
    public function getUsersRelatedByVote1(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersRelatedByVote1Partial && !$this->isNew();
        if (null === $this->collUsersRelatedByVote1 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByVote1) {
                // return empty collection
                $this->initUsersRelatedByVote1();
            } else {
                $collUsersRelatedByVote1 = ChildUserQuery::create(null, $criteria)
                    ->filterByWineRelatedByVote1($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersRelatedByVote1Partial && count($collUsersRelatedByVote1)) {
                        $this->initUsersRelatedByVote1(false);

                        foreach ($collUsersRelatedByVote1 as $obj) {
                            if (false == $this->collUsersRelatedByVote1->contains($obj)) {
                                $this->collUsersRelatedByVote1->append($obj);
                            }
                        }

                        $this->collUsersRelatedByVote1Partial = true;
                    }

                    return $collUsersRelatedByVote1;
                }

                if ($partial && $this->collUsersRelatedByVote1) {
                    foreach ($this->collUsersRelatedByVote1 as $obj) {
                        if ($obj->isNew()) {
                            $collUsersRelatedByVote1[] = $obj;
                        }
                    }
                }

                $this->collUsersRelatedByVote1 = $collUsersRelatedByVote1;
                $this->collUsersRelatedByVote1Partial = false;
            }
        }

        return $this->collUsersRelatedByVote1;
    }

    /**
     * Sets a collection of ChildUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $usersRelatedByVote1 A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildWine The current object (for fluent API support)
     */
    public function setUsersRelatedByVote1(Collection $usersRelatedByVote1, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $usersRelatedByVote1ToDelete */
        $usersRelatedByVote1ToDelete = $this->getUsersRelatedByVote1(new Criteria(), $con)->diff($usersRelatedByVote1);


        $this->usersRelatedByVote1ScheduledForDeletion = $usersRelatedByVote1ToDelete;

        foreach ($usersRelatedByVote1ToDelete as $userRelatedByVote1Removed) {
            $userRelatedByVote1Removed->setWineRelatedByVote1(null);
        }

        $this->collUsersRelatedByVote1 = null;
        foreach ($usersRelatedByVote1 as $userRelatedByVote1) {
            $this->addUserRelatedByVote1($userRelatedByVote1);
        }

        $this->collUsersRelatedByVote1 = $usersRelatedByVote1;
        $this->collUsersRelatedByVote1Partial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countUsersRelatedByVote1(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersRelatedByVote1Partial && !$this->isNew();
        if (null === $this->collUsersRelatedByVote1 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByVote1) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsersRelatedByVote1());
            }

            $query = ChildUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWineRelatedByVote1($this)
                ->count($con);
        }

        return count($this->collUsersRelatedByVote1);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     */
    public function addUserRelatedByVote1(ChildUser $l)
    {
        if ($this->collUsersRelatedByVote1 === null) {
            $this->initUsersRelatedByVote1();
            $this->collUsersRelatedByVote1Partial = true;
        }

        if (!$this->collUsersRelatedByVote1->contains($l)) {
            $this->doAddUserRelatedByVote1($l);
        }

        return $this;
    }

    /**
     * @param ChildUser $userRelatedByVote1 The ChildUser object to add.
     */
    protected function doAddUserRelatedByVote1(ChildUser $userRelatedByVote1)
    {
        $this->collUsersRelatedByVote1[]= $userRelatedByVote1;
        $userRelatedByVote1->setWineRelatedByVote1($this);
    }

    /**
     * @param  ChildUser $userRelatedByVote1 The ChildUser object to remove.
     * @return $this|ChildWine The current object (for fluent API support)
     */
    public function removeUserRelatedByVote1(ChildUser $userRelatedByVote1)
    {
        if ($this->getUsersRelatedByVote1()->contains($userRelatedByVote1)) {
            $pos = $this->collUsersRelatedByVote1->search($userRelatedByVote1);
            $this->collUsersRelatedByVote1->remove($pos);
            if (null === $this->usersRelatedByVote1ScheduledForDeletion) {
                $this->usersRelatedByVote1ScheduledForDeletion = clone $this->collUsersRelatedByVote1;
                $this->usersRelatedByVote1ScheduledForDeletion->clear();
            }
            $this->usersRelatedByVote1ScheduledForDeletion[]= $userRelatedByVote1;
            $userRelatedByVote1->setWineRelatedByVote1(null);
        }

        return $this;
    }

    /**
     * Clears out the collUsersRelatedByVote2 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsersRelatedByVote2()
     */
    public function clearUsersRelatedByVote2()
    {
        $this->collUsersRelatedByVote2 = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsersRelatedByVote2 collection loaded partially.
     */
    public function resetPartialUsersRelatedByVote2($v = true)
    {
        $this->collUsersRelatedByVote2Partial = $v;
    }

    /**
     * Initializes the collUsersRelatedByVote2 collection.
     *
     * By default this just sets the collUsersRelatedByVote2 collection to an empty array (like clearcollUsersRelatedByVote2());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsersRelatedByVote2($overrideExisting = true)
    {
        if (null !== $this->collUsersRelatedByVote2 && !$overrideExisting) {
            return;
        }
        $this->collUsersRelatedByVote2 = new ObjectCollection();
        $this->collUsersRelatedByVote2->setModel('\WineTasting\Model\User');
    }

    /**
     * Gets an array of ChildUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildWine is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     * @throws PropelException
     */
    public function getUsersRelatedByVote2(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersRelatedByVote2Partial && !$this->isNew();
        if (null === $this->collUsersRelatedByVote2 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByVote2) {
                // return empty collection
                $this->initUsersRelatedByVote2();
            } else {
                $collUsersRelatedByVote2 = ChildUserQuery::create(null, $criteria)
                    ->filterByWineRelatedByVote2($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersRelatedByVote2Partial && count($collUsersRelatedByVote2)) {
                        $this->initUsersRelatedByVote2(false);

                        foreach ($collUsersRelatedByVote2 as $obj) {
                            if (false == $this->collUsersRelatedByVote2->contains($obj)) {
                                $this->collUsersRelatedByVote2->append($obj);
                            }
                        }

                        $this->collUsersRelatedByVote2Partial = true;
                    }

                    return $collUsersRelatedByVote2;
                }

                if ($partial && $this->collUsersRelatedByVote2) {
                    foreach ($this->collUsersRelatedByVote2 as $obj) {
                        if ($obj->isNew()) {
                            $collUsersRelatedByVote2[] = $obj;
                        }
                    }
                }

                $this->collUsersRelatedByVote2 = $collUsersRelatedByVote2;
                $this->collUsersRelatedByVote2Partial = false;
            }
        }

        return $this->collUsersRelatedByVote2;
    }

    /**
     * Sets a collection of ChildUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $usersRelatedByVote2 A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildWine The current object (for fluent API support)
     */
    public function setUsersRelatedByVote2(Collection $usersRelatedByVote2, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $usersRelatedByVote2ToDelete */
        $usersRelatedByVote2ToDelete = $this->getUsersRelatedByVote2(new Criteria(), $con)->diff($usersRelatedByVote2);


        $this->usersRelatedByVote2ScheduledForDeletion = $usersRelatedByVote2ToDelete;

        foreach ($usersRelatedByVote2ToDelete as $userRelatedByVote2Removed) {
            $userRelatedByVote2Removed->setWineRelatedByVote2(null);
        }

        $this->collUsersRelatedByVote2 = null;
        foreach ($usersRelatedByVote2 as $userRelatedByVote2) {
            $this->addUserRelatedByVote2($userRelatedByVote2);
        }

        $this->collUsersRelatedByVote2 = $usersRelatedByVote2;
        $this->collUsersRelatedByVote2Partial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countUsersRelatedByVote2(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersRelatedByVote2Partial && !$this->isNew();
        if (null === $this->collUsersRelatedByVote2 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByVote2) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsersRelatedByVote2());
            }

            $query = ChildUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWineRelatedByVote2($this)
                ->count($con);
        }

        return count($this->collUsersRelatedByVote2);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     */
    public function addUserRelatedByVote2(ChildUser $l)
    {
        if ($this->collUsersRelatedByVote2 === null) {
            $this->initUsersRelatedByVote2();
            $this->collUsersRelatedByVote2Partial = true;
        }

        if (!$this->collUsersRelatedByVote2->contains($l)) {
            $this->doAddUserRelatedByVote2($l);
        }

        return $this;
    }

    /**
     * @param ChildUser $userRelatedByVote2 The ChildUser object to add.
     */
    protected function doAddUserRelatedByVote2(ChildUser $userRelatedByVote2)
    {
        $this->collUsersRelatedByVote2[]= $userRelatedByVote2;
        $userRelatedByVote2->setWineRelatedByVote2($this);
    }

    /**
     * @param  ChildUser $userRelatedByVote2 The ChildUser object to remove.
     * @return $this|ChildWine The current object (for fluent API support)
     */
    public function removeUserRelatedByVote2(ChildUser $userRelatedByVote2)
    {
        if ($this->getUsersRelatedByVote2()->contains($userRelatedByVote2)) {
            $pos = $this->collUsersRelatedByVote2->search($userRelatedByVote2);
            $this->collUsersRelatedByVote2->remove($pos);
            if (null === $this->usersRelatedByVote2ScheduledForDeletion) {
                $this->usersRelatedByVote2ScheduledForDeletion = clone $this->collUsersRelatedByVote2;
                $this->usersRelatedByVote2ScheduledForDeletion->clear();
            }
            $this->usersRelatedByVote2ScheduledForDeletion[]= $userRelatedByVote2;
            $userRelatedByVote2->setWineRelatedByVote2(null);
        }

        return $this;
    }

    /**
     * Clears out the collUsersRelatedByVote3 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsersRelatedByVote3()
     */
    public function clearUsersRelatedByVote3()
    {
        $this->collUsersRelatedByVote3 = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsersRelatedByVote3 collection loaded partially.
     */
    public function resetPartialUsersRelatedByVote3($v = true)
    {
        $this->collUsersRelatedByVote3Partial = $v;
    }

    /**
     * Initializes the collUsersRelatedByVote3 collection.
     *
     * By default this just sets the collUsersRelatedByVote3 collection to an empty array (like clearcollUsersRelatedByVote3());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsersRelatedByVote3($overrideExisting = true)
    {
        if (null !== $this->collUsersRelatedByVote3 && !$overrideExisting) {
            return;
        }
        $this->collUsersRelatedByVote3 = new ObjectCollection();
        $this->collUsersRelatedByVote3->setModel('\WineTasting\Model\User');
    }

    /**
     * Gets an array of ChildUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildWine is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     * @throws PropelException
     */
    public function getUsersRelatedByVote3(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersRelatedByVote3Partial && !$this->isNew();
        if (null === $this->collUsersRelatedByVote3 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByVote3) {
                // return empty collection
                $this->initUsersRelatedByVote3();
            } else {
                $collUsersRelatedByVote3 = ChildUserQuery::create(null, $criteria)
                    ->filterByWineRelatedByVote3($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersRelatedByVote3Partial && count($collUsersRelatedByVote3)) {
                        $this->initUsersRelatedByVote3(false);

                        foreach ($collUsersRelatedByVote3 as $obj) {
                            if (false == $this->collUsersRelatedByVote3->contains($obj)) {
                                $this->collUsersRelatedByVote3->append($obj);
                            }
                        }

                        $this->collUsersRelatedByVote3Partial = true;
                    }

                    return $collUsersRelatedByVote3;
                }

                if ($partial && $this->collUsersRelatedByVote3) {
                    foreach ($this->collUsersRelatedByVote3 as $obj) {
                        if ($obj->isNew()) {
                            $collUsersRelatedByVote3[] = $obj;
                        }
                    }
                }

                $this->collUsersRelatedByVote3 = $collUsersRelatedByVote3;
                $this->collUsersRelatedByVote3Partial = false;
            }
        }

        return $this->collUsersRelatedByVote3;
    }

    /**
     * Sets a collection of ChildUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $usersRelatedByVote3 A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildWine The current object (for fluent API support)
     */
    public function setUsersRelatedByVote3(Collection $usersRelatedByVote3, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $usersRelatedByVote3ToDelete */
        $usersRelatedByVote3ToDelete = $this->getUsersRelatedByVote3(new Criteria(), $con)->diff($usersRelatedByVote3);


        $this->usersRelatedByVote3ScheduledForDeletion = $usersRelatedByVote3ToDelete;

        foreach ($usersRelatedByVote3ToDelete as $userRelatedByVote3Removed) {
            $userRelatedByVote3Removed->setWineRelatedByVote3(null);
        }

        $this->collUsersRelatedByVote3 = null;
        foreach ($usersRelatedByVote3 as $userRelatedByVote3) {
            $this->addUserRelatedByVote3($userRelatedByVote3);
        }

        $this->collUsersRelatedByVote3 = $usersRelatedByVote3;
        $this->collUsersRelatedByVote3Partial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countUsersRelatedByVote3(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersRelatedByVote3Partial && !$this->isNew();
        if (null === $this->collUsersRelatedByVote3 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersRelatedByVote3) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsersRelatedByVote3());
            }

            $query = ChildUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByWineRelatedByVote3($this)
                ->count($con);
        }

        return count($this->collUsersRelatedByVote3);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\WineTasting\Model\Wine The current object (for fluent API support)
     */
    public function addUserRelatedByVote3(ChildUser $l)
    {
        if ($this->collUsersRelatedByVote3 === null) {
            $this->initUsersRelatedByVote3();
            $this->collUsersRelatedByVote3Partial = true;
        }

        if (!$this->collUsersRelatedByVote3->contains($l)) {
            $this->doAddUserRelatedByVote3($l);
        }

        return $this;
    }

    /**
     * @param ChildUser $userRelatedByVote3 The ChildUser object to add.
     */
    protected function doAddUserRelatedByVote3(ChildUser $userRelatedByVote3)
    {
        $this->collUsersRelatedByVote3[]= $userRelatedByVote3;
        $userRelatedByVote3->setWineRelatedByVote3($this);
    }

    /**
     * @param  ChildUser $userRelatedByVote3 The ChildUser object to remove.
     * @return $this|ChildWine The current object (for fluent API support)
     */
    public function removeUserRelatedByVote3(ChildUser $userRelatedByVote3)
    {
        if ($this->getUsersRelatedByVote3()->contains($userRelatedByVote3)) {
            $pos = $this->collUsersRelatedByVote3->search($userRelatedByVote3);
            $this->collUsersRelatedByVote3->remove($pos);
            if (null === $this->usersRelatedByVote3ScheduledForDeletion) {
                $this->usersRelatedByVote3ScheduledForDeletion = clone $this->collUsersRelatedByVote3;
                $this->usersRelatedByVote3ScheduledForDeletion->clear();
            }
            $this->usersRelatedByVote3ScheduledForDeletion[]= $userRelatedByVote3;
            $userRelatedByVote3->setWineRelatedByVote3(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUserRelatedBySubmitter) {
            $this->aUserRelatedBySubmitter->removeWineRelatedBySubmitter($this);
        }
        $this->idwine = null;
        $this->name = null;
        $this->picture = null;
        $this->year = null;
        $this->idsubmitter = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collUsersRelatedByVote1) {
                foreach ($this->collUsersRelatedByVote1 as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsersRelatedByVote2) {
                foreach ($this->collUsersRelatedByVote2 as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsersRelatedByVote3) {
                foreach ($this->collUsersRelatedByVote3 as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUsersRelatedByVote1 = null;
        $this->collUsersRelatedByVote2 = null;
        $this->collUsersRelatedByVote3 = null;
        $this->aUserRelatedBySubmitter = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(WineTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
