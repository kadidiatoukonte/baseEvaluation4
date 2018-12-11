<?php

// We register our autoload.
function chargerClasse($classname)
{
    if(file_exists('../models/'. $classname.'.php'))
    {
        require '../models/'. $classname.'.php';
    }
    else 
    {
        require '../entities/' . $classname . '.php';
    }
}

spl_autoload_register('chargerClasse');
   
$db = Database::DB();

/**
 * Declare new objet
 */
$accountManager = new AccountManager($db);

/**
 * Create account
 */
$accounts = ['PEL', 'Compte joint', 'Livret A', 'Compte courant'];
$nameAccount = ['Compte courant', 'Livret A', 'Compte joint', 'PEL'];


    // checking for create account
    if (isset($_POST['name'])) {
        if ($_POST['name'] == 'PEL' || $_POST['name'] == 'Compte Courant' || $_POST['name'] == 'Livret A' || $_POST['name'] == 'Compte Joint') {
            // instance new account
            if ($_POST['name'] == "Compte Courant") {
                $createAccount = new Account([
                'name' => $_POST['name'],
                'balance' => 80,
                'id' => $_POST['id']
            ]);
            } else {
                $createAccount = new Account([
                'name' => $_POST['name'],
                'balance' => 0,
                'id' => $_POST['id']
            ]);
            }
            
        }
    }


    // Our manager is responsible for registering our new object $ newAccount in database
    if (isset($_GET['start'])) {
        if ($_GET['start'] == 'loading') {
            if (isset($_POST["name"]) && isset($_POST["balance"])) {
                $account = new Account([
            "name" => $_POST['name'],
            "balance" => 80
        ]);
                $accountManager->addAccount($account);
            }
        }
    }

    /**
     * Loop to deposit money
     */
    if (!empty($_POST['balance']) && !empty($_POST['payment']) && !empty($_POST['id'])) {
        $balance = htmlspecialchars($_POST['balance']);
        $payment = htmlspecialchars($_POST['payment']);
        $id = htmlspecialchars($_POST['id']);
        $getaccount = $accountManager->getAccountId($id);
        $getaccount->deposer($balance);
        $accountManager->update($getaccount);
    }

    /**
     * Loop to withdraw money
     */
    if (!empty($_POST['balance']) && !empty($_POST['debit']) && !empty($_POST['id'])) {
        $balance = htmlspecialchars($_POST['balance']);
        $debit = htmlspecialchars($_POST['debit']);
        $id = htmlspecialchars($_POST['id']);
        $getaccount = $accountManager->getAccountId($id);
        $getaccount->retrait($balance);
        $accountManager->update($getaccount);
    }

    /**
     * Function to delete
     */
    if(isset($_POST["id"]) && isset($_POST["delete"]))
    {
        $id = htmlspecialchars($_POST['id']);
        $delete = htmlspecialchars($_POST['delete']);
        $account = $_POST['id'];
        $accountManager->delete($account);
    }

    /**
     * Function to transfert
     */
    if (!empty($_POST['idDebit']) && !empty($_POST['idPayment']) && !empty($_POST['balance']) && !empty($_POST['transfer'])) {
        $idDebit = htmlspecialchars(addslashes(strip_tags($_POST['idDebit'])));
        $idPayment = htmlspecialchars(addslashes(strip_tags($_POST['idPayment'])));
        $balance = htmlspecialchars(addslashes(strip_tags($_POST['balance'])));
        $transfert = htmlspecialchars(addslashes(strip_tags($_POST['transfer'])));
        if ($idPayment !== $idDebit) {
            $give = $accountManager->getAccountId($idDebit);
            $take = $accountManager->getAccountId($idPayment);
            $give->transfert($take, $balance);
            $accountManager->update($give);
            $accountManager->update($take);
        }
    }
    
/**
 * Stores in the $accounts variable
 */    
$accounts = $accountManager->getAccounts();

include "../views/indexView.php";
?>

