<?php

namespace KDE\Model;

use TBCore\Model\Model;

/**
 * Class Board
 * @TBCore\Annotation\Table("KdeBoard")
 * @package KDE\Model\Kde
 */
class Board extends Model
{
    /**
     * @TBCore\Annotation\PrimaryKey
     * @var integer
     */
    protected $boardId = 0;

    /**
     * @var string
     */
    protected $title = "";

    /**
     * valid json format
     * @var string
     */
    protected $content = "{}";

    /**
     * @var integer
     */
    protected $rows = 0;

    /**
     * @var integer
     */
    protected $columns = 0;

    /**
     * @return int
     */
    public function getBoardId(): int
    {
        return $this->boardId;
    }

    /**
     * @param int $boardId
     * @return Board
     */
    public function setBoardId(int $boardId): Board
    {
        $this->boardId = $boardId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Board
     */
    public function setTitle(string $title): Board
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        if ($this->content === "") {
            return "{}";
        }
        return $this->content;
    }

    /**
     * @param string $content
     * @return Board
     */
    public function setContent(string $content): Board
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     * @return Board
     */
    public function setRows(int $rows): Board
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @return int
     */
    public function getColumns(): int
    {
        return $this->columns;
    }

    /**
     * @param int $columns
     * @return Board
     */
    public function setColumns(int $columns): Board
    {
        $this->columns = $columns;
        return $this;
    }

}
