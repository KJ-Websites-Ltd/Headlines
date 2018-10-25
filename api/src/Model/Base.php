<?php

namespace Headline\Model;

use Cocur\Slugify\Slugify;

class Base extends \Headline\Base
{

    private $db;
    private $table;
    private $item;
    private $fields;
    private $slugify;

    public function __construct($container)
    {

        parent::__construct($container);
        $this->setDb($this->getContainer()->get('db'));

    }

    /**
     * @brief abstract fetch one item from a query
     * @details [long description]
     * @return [description]
     */
    public function fetchOne($query, $params)
    {

        $stmt = $this->getDb()->query($query);
        $stmt->execute($params);
        return $stmt->fetch();

    }

    /**
     * @brief abstract fetch all items
     * @details [long description]
     * @return [description]
     */
    public function fetchAll($query, $params)
    {

        $stmt = $this->getDb()->query($query);
        $stmt->execute($params);
        return $stmt->fetchAll();

    }

    /**
     * @brief Post a query to the db
     * @details [long description]
     * @return [description]
     */
    public function postOne($query, $params)
    {

        $stmt = $this->getDb()->prepare($query)->execute($params);
        $id   = $this->getDb()->lastInsertId();

        return ['id' => $id, 'stmt' => $stmt];

    }

    /**
     * @brief get the db connection
     * @details [long description]
     * @return [description]
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * set the db connection
     */
    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    /**
     * @brief get the db table in use
     * @details [long description]
     * @return [description]
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @brief set the db table in use
     * @details [long description]
     * @return [description]
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @brief get the fields to use for a table
     * @details [long description]
     * @return [description]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * set the field to use for a table
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * get the slugify object
     *
     * @return void
     */
    public function getSlugify()
    {
        if (empty($this->slugify)) {
            $this->setSlugify();
        }
        return $this->slugify;
    }

    /**
     * set the slugify objecta
     *
     * @return void
     */
    public function setSlugify()
    {
        $this->slugify = new Slugify();
    }

}
