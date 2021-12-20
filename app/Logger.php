<?php

namespace app;

class Logger
{
    private DB $storage;

    public function __construct(DB $storage)
    {
        $this->storage = $storage;
    }

    private function getStorage(): DB
    {
        return $this->storage;
    }

    /**
     * @param string $message
     * @param mixed $data
     * @return void
     */
    public function log(string $message, mixed $data): void
    {
        $params = [
            'message' => $message,
            'data' => is_array($data) ? print_r($data, true) : $data
        ];

        $this->getStorage()->insert('logger', $params);
    }

}