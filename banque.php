/*
la gestion d'un compte dans un système bancaire se fait selon les règles suivantes:
+ chaque compte est associé à un et un seul client.
+ un compte est caractérisé par son numero, son type(compte particulier/compte courant) 
  son solde ,sa date de creation et ses mouvements
  (débit/crédit): débit ->un encaissement : crédit-> un retrait
+ un compte peut être créer même si'il n'y a pas un verssement initial
  pour simplification on prévoit de gérer un  compte client est ses comportements 
  par une seul classe d'objet suivant le menu ci-dessous:
  ++++++++Menu+++++++++++++
  1- créer un compte
  2- créer un encaissement
  3- créer un retrait
  4- les encaissements d'un compte client
  5- les retraits d'un d'un compte client
  6- les mouvements d'un compte (relevé bancaire)
  7- le solde d'un compte
  8-Fin du programme
  NB: un client est connu par son CINN° (personne physique)/ RC(register de commerce : personne morale)
    pour cet exercice le calcul des commissions ne son pas inclus */
<?php


class Comptes{
    private $numero;
    private $type;
    private $dateCreation;
    private $solde;
    // mouvement entre/sortie
    private $me;
    private $ms;
    // counter for counting operations for each client
    private $counter ; 
    // constructor
    function __construct($numero, $type, $dateCreation, $solde = 0, $me = [], $ms = [], $counter = 0){
        $this->numero = $numero;
        $this->type = $type;
        $this->solde = $solde;
        $this->date =$dateCreation;
        $this->me = $me;
        $this->ms = $ms;
        $this->counter = $counter;
    }
    // getters
    function getNumero(){
        return $this->numero;
    }
    function getType(){
        return $this->type;
    }
    function getDate(){
        return $this->date;
    }
    function getSolde(){
        return $this->solde;
    }
    function getME(){
        return $this->me;
    }
    function getMS(){
        return $this->ms;
    }
    function getCounter(){
        return $this->counter;
    }
    // setters
    function setNumero($value){
       $this->numero = $value;
    }
    function setType($value){
       $this->type = $value;
    }
    function setDate($value){
       $this->date = $value;
    }
    function setSolde($value){
       $this->solde = $value;
    }
    // setting mouvement entre
    function setME($key,$value){
       $this->me[$key]=$value;
    }
    // setting mouvement sortie
    function setMS($key,$value){
        $this->ms[$key]=$value;
    }
    function setCounter($value){
        $this->counter = $value;
    }
    
    
}

$comptes = [];

// 1- nouveau client
function ajouterCompte(&$comptes){
    $rep = true; 
    while($rep){
        $numero = readline("Entrer le numero de compte(le numero doit etre 8 caracteres): ");
        while(strlen($numero)!== 4){
            $numero = readline("Entrer le numero de compte(le numero doit etre 8 caracteres): ");
        }
        
        $type = readline("Entrer le type de compte particulier/courant: ");
        $dateCreation = date("d-m-Y");
        $choix = readline("Tu veux faire un versement initial ? O/N ");
        if($choix === "o"){
            $solde = (float)readline("Entrer le montant a verser: ");
            $nouveauClient = new Comptes($numero, $type,$dateCreation,$solde);
            $comptes[$numero] = $nouveauClient;
        }else{
            $nouveauClient = new Comptes($numero, $type, $dateCreation);
            $comptes[$numero] = $nouveauClient;
        }
        $choix = readline("Entrer un autre compte? O/N");
        if($choix === 'n') $rep = false;
    }
}

// 2- Encaissement operation
function encaissementOp(&$comptes){
    $rep = true; 
    while($rep){
    $numero = readline("Entrer le numero de client: ");
    $montant = (float)readline('Entrer le montant a encaisser: ');
    $newSolde = $comptes[$numero]->getSolde() + $montant;
    $comptes[$numero]->setSolde($newSolde);
    $counter = $comptes[$numero]->getCounter();
    $counter+=1;
    $comptes[$numero]->setCounter($counter);
    $dateOperation = date("d-m-Y") . "/".$comptes[$numero]->getCounter();
    $comptes[$numero]->setME($dateOperation,$montant);
    $choix = readline("Ajouter un autre encaissement? O/N ");
        if($choix === 'n') $rep = false;
    }
}
// 3- Retrait operation
function retraitOp(&$comptes){ 
    $rep = true; 
    while($rep){
    $numero = readline("Entrer le numero de client: ");
    $montant = (float)readline('Entrer le montant a retraiter: ');
    $newSolde = $comptes[$numero]->getSolde() - $montant;
    $comptes[$numero]->setSolde($newSolde);
    $counter = $comptes[$numero]->getCounter();
    $counter+=1;
    $comptes[$numero]->setCounter($counter);
    $dateOperation = date("d-m-Y") . "/".$comptes[$numero]->getCounter();
    $comptes[$numero]->setMS($dateOperation,$montant);
    $choix = readline("Ajouter un autre retrait? O/N ");
        if($choix === 'n') $rep = false;
    }
}

    
// 4- Les encaissements
function encaissments($comptes){
    $rep = true; 
    while($rep){
    $total = 0;
    $numero = readline("Entrer le numero de client: ");
    $ME = $comptes[$numero]->getME();
    foreach($ME as $date => $mouvement){
        $total+= $mouvement;
        echo "$date: " . $mouvement ."DH\n";
    }
    echo "-------------------------------\n";
    echo "Total des encaissments: $total\n";
    echo "-------------------------------\n";
    $choix = readline("Voir les encaissements d'un autre client? O/N ");
    if($choix === 'n') $rep = false;
}
}

// 5- Les retraits
function retraits($comptes){
    $rep = true; 
    while($rep){
    $total = 0;
    $numero = readline("Entrer le numero de client: ");
    $MS = $comptes[$numero]->getMS();
    foreach($MS as $date => $mouvement){
        $total+= $mouvement;
        echo "$date: " . $mouvement ."DH\n";
    }
    echo "-------------------------------\n";
    echo "Total des retraits : $total\n";
    echo "-------------------------------\n";
    $choix = readline("Voir les retraits d'un autre client? O/N ");
    if($choix === 'n') $rep = false;
}
}

// 6- les mouvements
function mouvements($comptes){
    $rep = true; 
    while($rep){
    $numero = readline("Entrer le numero de client: ");
    $ME= $comptes[$numero]->getME();
    $MS = $comptes[$numero]->getMS();
    $length = count($ME) +  count($MS);
    for($i = 1 ; $i<$length +1; $i++){
        foreach ($ME as $date => $montant) {
          if($i == substr($date,11)){
              echo "E: $date ------> $montant\n";
              break;
          }
        }
        foreach ($MS as $date => $montant) {
            if($i == substr($date,11)){
                echo "S: $date ------> $montant\n";
                break;
            }
        }
    }
    $choix = readline("Voir les mouvements d'un autre client? O/N ");
    if($choix === 'n') $rep = false;
}
}

// 7- Solde
function solde($comptes){
    $rep = true;
    while($rep){
        $numero = readline("Entrer le numero de client: ");
        $solde= $comptes[$numero]->getSolde();
        echo "\n---------------------------------------------------\n";
        echo "      Votre solde est: " . $comptes[$numero]->getSolde() . "\n";
        echo "---------------------------------------------------\n";
        $choix = readline("Voir le solde d'un autre client? O/N ");
        if($choix === 'n') $rep = false;
    }
}





function menu(){
    $rep = true;
    while($rep){
        echo "1- Créer un compte.
2- Créer un encaissement.
3- Créer un retrait.
4- Les encaissements d'un compte client.
5- Les retraits d'un d'un compte client.
6- Les mouvements d'un compte (relevé bancaire).
7- Le solde d'un compte.
8- Fin du programme.\n";
       
        $choix = readline("Entrer votre choix : ");
        switch($choix){
            case "1": {ajouterCompte($comptes); break;}
            case "2": {encaissementOp($comptes); break;}
            case "3": {retraitOp($comptes); break;}
            case "4": {encaissments($comptes); break;}
            case "5": {retraits($comptes); break;}
            case "6": {mouvements($comptes); break;}
            case "7": {solde($comptes); break;}
            case "8": {$rep = false; break;}
        }
    }
}

menu();