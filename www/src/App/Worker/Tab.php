<?php


namespace KDE\Worker;

class Tab
{
    /**
     * @var bool
     */
    private $active = false;
    /**
     * @var string
     */
    private $link = "";

    /**
     * @var string
     */
    private $title = "";

    public function __construct($active, $link, $title)
    {
        $this->active = $active;
        $this->link = $link;
        $this->title = $title;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Tab
     */
    public function setActive(bool $active): Tab
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return Tab
     */
    public function setLink(string $link): Tab
    {
        $this->link = $link;
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
     * @return Tab
     */
    public function setTitle(string $title): Tab
    {
        $this->title = $title;
        return $this;
    }
}
