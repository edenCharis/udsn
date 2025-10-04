<?php 

include '../php/connexion.php';
include '../php/lib.php';
session_start();

 error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST"){



    $enseignant_id = clean_input($_POST["enseignant"]);
     $annee = clean_input($_POST["annee"]);

    $etab = clean_input($_POST["etab"]);
    $ecues =$_POST["ecues"];

    $code_contrat = generateCodeContrat();
 



      
             

           $requete = "INSERT into contrat(numero_contrat,enseignant,etab,annee) values ('$code_contrat',$enseignant_id,'$etab','$annee')";


           if($connexion->query($requete) and $connexion->query("update enseignant set contrat='$code_contrat' where id=$enseignant_id"))
           {
                 foreach($ecues as $e){


                        $st="INSERT into contrat_couverture(contrat,ecue,etab) values ('$code_contrat','$e','$etab')";
                        $connexion->query($st);
                 }
       

                 logUserAction($connexion,$_SESSION['id_user'],"enregistrement d'un contrat ",date("Y-m-d H:i:s"),$userIP,"code contrat : $code_contrat");

                 header("location: contrat?sucess=Opération effectuée avec succèss");
           }else{

            header("location: contrat?erreur=$connexion->error");


           }
          
    








}