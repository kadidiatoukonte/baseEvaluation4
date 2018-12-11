<?php

declare(strict_types = 1);

class AccountManager
{
    private $_db;

    /**
     * Constructor
     *
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }
    
    /**
     * Set the value of _db
     *
     * @param PDO $db
     * @return  self
     */
    public function setDb(PDO $db)
    {
        $this->_db = $db;
        return $this;
    }
    
    /**
     * Get the value of _db
     *
     * @return self
     */
    public function getDb()
    {
        return $this->_db;
    }
    
      
    /**
     * Get all accounts. It returns an array of objects $account
     *
     * @return array $arrayAccounts
     */
    public function getAccounts()
    {
        $arrayOfAccounts = [];
        $query = $this->getDb()->query('SELECT * FROM accounts');
        $accounts = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // Each turn, on a new Account object that is stored in $ arrayOfAccounts []
        foreach ($accounts as $Account) {
            $arrayOfAccounts[] = new Account($Account);
        }
        // We return the table containing all our objects Account
        return $arrayOfAccounts;
    }

    /**
     * Get one account by id or name
     *
     * @param $id
     * @return Account
     */
    
    public function getAccountId(int $id)
    {
        $accountId = "";
        $addBalance = "";
        $accountById = $this->getDb()->prepare("SELECT * FROM accounts WHERE id = :id");
        $accountById->execute(array(
            'id' => $id
        ));
        $accounts = $accountById->fetchAll(PDO::FETCH_ASSOC);
        foreach ($accounts as $Account) {
            $accountId = new Account($Account);
        }
        return $accountId;
    }
   
    /**
     * Check if account exists or not
     *
     * @param string $name
     * @return boolean
     */
    public function checkIfExist(string $name)
    {
        $query = $this->getDb()->prepare('SELECT * FROM accounts WHERE name = :name');
        $query->bindValue('name', $name, PDO::PARAM_STR);
        $query->execute();

        // If there is an entry with this name, it means that there is
        if ($query->rowCount() > 0) {
            return true;
        }
        
        // Otherwise it does not exist
        return false;
    }
    
    /**
     * Add account in DB
     *
     * @param Account $account
     */
    public function addAccount(Account $account)
    {
        $query = $this->getDb()->prepare('INSERT INTO accounts(name, balance) VALUES(:name, :balance)');
        $query->bindValue('name', $account->getName(), PDO::PARAM_STR);
        $query->bindValue('balance', $account->getBalance(), PDO::PARAM_INT);
    }

    /**
     * Delete account from DB
     *
     * @param Account $account
     */
    public function delete($id)
    {
        $query = $this->getDb()->prepare('DELETE FROM accounts WHERE id = :id');
        $query->bindValue('id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Update account's data
     *
     * @param Character $account
     */
    public function update(Account $account)
    {
        $query = $this->getDb()->prepare('UPDATE accounts SET balance = :balance WHERE id = :id');
        $query->bindValue('balance', $account->getBalance(), PDO::PARAM_INT);
        $query->bindValue('id', $account->getId(), PDO::PARAM_INT);
        $query->execute();
    }
}
