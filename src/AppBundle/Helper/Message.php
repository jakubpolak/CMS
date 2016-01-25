<?php

namespace  AppBundle\Message;

/**
 * @author Jakub Polák
 */
class Message {
    const TYPE_SUCCESS = 'success';
    const TYPE_DANGER = 'danger';
    const TYPE_INFO = 'info';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $text;

    /**
     * Constructor.
     *
     * @param string $type
     * @param string $text
     */
    public function __construct(string $type, string $text) {
        $this->type = $type;
        $this->text = $text;
    }

    /**
     * Get message type.
     *
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * Get message text.
     *
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }
}