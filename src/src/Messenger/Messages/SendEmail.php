<?php


namespace App\Messenger\Messages;


class SendEmail
{

    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $emailId;

    public function __construct(int $userId, string $emailId)
    {
        $this->userId = $userId;
        $this->emailId = $emailId;
    }

    /**
     * @return string
     */
    public function getEmailId(): string
    {
        return $this->emailId;
    }

    /**
     * @param string $emailId
     */
    public function setEmailId(string $emailId): void
    {
        $this->emailId = $emailId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}