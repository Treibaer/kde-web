<?php

namespace KDE\Model;

use TBCore\Model\Model;

/**
 * Class Card
 * @TBCore\Annotation\Table("KdeCard")
 * @package TB\Model\Kde
 */
class Card extends Model
{
    /**
     * @TBCore\Annotation\PrimaryKey
     * @var integer
     */
    protected int $cardId = 0;

    protected string $title = "";

    protected string $imageUrl = "";

    protected string $type = "";

    /**
     *
     * @TBCore\Annotation\Hidden
     * @var string
     */

    public string $originalImageUrl = "";

    /**
     *
     * @TBCore\Annotation\Hidden
     * @var string
     */
    protected string $thumbImageUrl = "";

    public function __construct($o = null)
    {
        parent::__construct($o);
        $this->originalImageUrl = $this->imageUrl;
        $this->thumbImageUrl = $this->imageUrl;
    }

    /**
     * @return int
     */
    public function getCardId(): int
    {
        return $this->cardId;
    }

    /**
     * @param int $cardId
     * @return Card
     */
    public function setCardId(int $cardId): Card
    {
        $this->cardId = $cardId;
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
     * @return Card
     */
    public function setTitle(string $title): Card
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return Card
     */
    public function setImageUrl(string $imageUrl): Card
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Card
     */
    public function setType(string $type): Card
    {
        $this->type = $type;
        return $this;
    }
}
