<?php

namespace app;

use \Exception;

class User
{
    /* @var $id integer */
    public int $id;

    /* @var $name string */
    public string $name;

    /* @var $email string */
    public string $email;

    /* @var $created string */
    public string $created;

    /* @var $deleted null|string */
    public ?string $deleted;

    /* @var $notes null|string */
    public ?string $notes;

    /* @var $db DB */
    private DB $db;

    /* @var $tableName string */
    private string $tableName = 'users';

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    private function getDb(): DB
    {
        return $this->db;
    }

    private function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param int $id
     * @return $this
     * @throws Exception
     */
    public function getOne(int $id): User
    {
        $user = $this->getDb()->find($this->getTableName(), ['id' => $id]);

        if ($user) {
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->created = $user['created'];
            $this->deleted = $user['deleted'];
            $this->notes = $user['notes'];

            return $this;
        }

        throw new Exception('User not found');
    }


    /**
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function create(array $params): bool
    {
        // check users params
        $this->checkParams($params);

        // disable change created and deleted value
        unset($params['created'], $params['deleted']);
        $params['created'] = date('%Y-%m-%d %H:%i:s');
        $params['deleted'] = null;

        try {
            return $this->getDb()->insert($this->getTableName(), $params);
        } catch (Exception $e) {
            throw new Exception('Error creating user. Db error: ' . $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function update(int $id, array $params): bool
    {
        // check users params
        $this->checkParams($params);

        // disable change created and deleted value
        unset($params['created'], $params['deleted']);

        try {
            $updated = $this->getDb()->update($this->getTableName(), ['id' => $id], $params);
            // add to log all user update
            (new Logger($this->getDb()))->log("User updated ($id).");

            return $updated;
        } catch (Exception $e) {
            throw new Exception('Error updating user. Db error: ' . $e->getMessage());
        }
    }

    /**
     * Set/unset user as deleted.
     * @param int $id
     * @param bool $isDelete
     * @return bool
     * @throws Exception
     */
    public function delete(int $id, bool $isDelete = true): bool
    {
        $deleted = $isDelete ? date('%Y-%m-%d %H:%i:s') : null;

        try {
            return $this->getDb()->update($this->getTableName(), ['id' => $id], ['deleted' => $deleted]);
        } catch (Exception $e) {
            throw new Exception('Error deleting user. Db error: ' . $e->getMessage());
        }
    }

    private function checkParams(array $params): void
    {
        if (!empty($params['name'])) {
            $this->checkNameValue($params['name']);
        }

        if (!empty($params['email'])) {
            $this->checkEmailValue($params['email']);
        }

        if (!empty($params['created']) && !empty($params['deleted'])) {
            $this->checkDeletedValue($params['created'], $params['deleted']);
        }
    }

    /**
     * Значение поля 'name' (имя пользователя):
     *  - может состоять только из символов a-z и 0-9;
     *  - не может быть короче 8 символов;
     *  - не должно содержать слов из списка запрещенных слов;
     *  - должно быть уникальным;
     * @param string $value
     * @return void
     * @throws Exception
     */
    private function checkNameValue(string $value): void
    {
        if (preg_match('/^([a-z0-9]){8,}$/', $value) !== 1) {
            throw new Exception("User name {$value} is incorrect!");
        }

        if (in_array($value, $this->getForbiddenWords())) {
            throw new Exception("User name {$value} forbidden!");
        }
    }

    private function getForbiddenWords(): array
    {
        return [
            'lamariaz', 'joshephina', 'putinovich'
        ];
    }

    /**
     * Значение поля 'email':
     *  - должно иметь корректный для e-mail адреса формат;
     *  - не должно принадлежать домену из списка "ненадежных" доменов;
     *  - должно быть уникальным;
     * @param string $value
     * @return void
     * @throws Exception
     */
    private function checkEmailValue(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("E-mail {$value} is incorrect!");
        }

        $parts = explode('@', $value);
        $domain = array_pop($parts);
        if (in_array($domain, $this->getForbiddenDomains())) {
            throw new Exception("E-mail domain {$value} forbidden!");
        }
    }

    private function getForbiddenDomains(): array
    {
        return [
            'school.edu', 'leika.com', 'elka.com'
        ];
    }

    /**
     * Значение поля 'deleted':
     *  - отражает факт "удаления" пользователя (т. н. "soft-delete");
     *  - не может быть меньше значения поля 'created';
     *  - для неудаленного, активного пользователя равно NULL;
     * @param string $created
     * @param string $deleted
     * @return void
     * @throws Exception
     */
    private function checkDeletedValue(string $created, string $deleted): void
    {
        if (strtotime($created) <= strtotime($deleted)) {
            throw new Exception("Deleted value {$deleted} is incorrect!");
        }
    }
}
