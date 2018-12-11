<?php

declare(strict_types = 1);

class Account
{
    protected $id;
    protected $name;
    protected $balance;
 
    /**
     * Function construct
     *
     * @param array $array
     * 
     * @return self
     */
    public function __construct(array $array)
    {
        $this->hydrate($array);
    }

    /**
     * Function hydrate
     * 
     * @param array $donnees
     */
          
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set'.ucfirst($key);
                          
            // Si le setter correspondant existe.
            if (method_exists($this, $method)) {
                // On appelle le setter.
                $this->$method($value);
            }
        }
    }    

    /**
     * Get the value of id
     * 
     * @return self
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     * 
     * @return self
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of balance
     * 
     * @return self
     */
    public function getBalance()
    {
        // $balance = (int) $balance;
        return $this->balance;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     * 
     * @return  self
     */
    public function setId($id)
    {
        $id = (int) $id;
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of name
     *
     * @param str $name
     * 
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the value of balance
     *
     * @param int $balance
     * 
     * @return  self
     */
    public function setBalance($balance)
    {
        $balance = (int) $balance;
        
        $this->balance = $balance;

        return $this;
    }

    /**
     * Undocumented function deposer
     *
     * @param int $montant
     * @return self
     */
    public function deposer($montant)
    {
        $montant = (int) $montant;
        $this->balance += $montant;
    }

    /**
     * Function retrait
     * 
     * @param int $montant
     * 
     * @return condition
     */                  
    public function retrait($montant)
    {
        if ($this->balance < $montant) {
            echo 'noooooooooo';
        } else {
            $this->balance -= $montant;
        }
    }

    /**
     *  Function transfert
     *
     * @param Account $account
     * @param int $balance
     * @return void
     */
    public function transfert(Account $account, $balance)
    {
        $balance = (int) $balance;
        $transfert = $account->getBalance() + $balance;
        $this->removeBalance($balance);
        return $account->setBalance($transfert);
    }

    /**
     * Undocumented function removeBalance
     *
     * @param int $balance
     * @return self
     */
    public function removeBalance($balance)
    {
        $balance = (int) $balance;
        $removeBalance = $this->getBalance() - $balance;
        return $this->setBalance($removeBalance);
    }
}
