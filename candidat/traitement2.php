<?php 

include '../php/connexion.php';
include '../php/lib.php';
session_start();

 

if ($_SERVER["REQUEST_METHOD"] == "POST"){


    $sql="UPDATE utilisateur set";

    if( isset($_POST['usernom']) ){

             $nom=str_replace("'","+",$_POST['usernom']);
             $sql.=" nom='".$nom."',";
    }

    if(isset($_POST['userlogin'])){
        $login=str_replace("'","+",$_POST['userlogin']);
        $sql.=" login='".$login."',";

    }

    if(isset($_POST['userpassword'])){
        $mdp=str_replace("'","+",$_POST['userpassword']);
        $sql.=" mdp='".$mdp."',";

    }
    
         
        if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
            // Récupérez des informations sur le fichier
            $nomFichier = $_FILES['img']['name'];
            $typeFichier = $_FILES['img']['type'];
            $cheminTemporaire = $_FILES['img']['tmp_name'];
    
            // Vous pouvez maintenant traiter le fichier, par exemple, le déplacer vers un répertoire de stockage
             $nouveauChemin = 'photos/' . $nomFichier;
    
            if (move_uploaded_file($cheminTemporaire, $nouveauChemin)) {

             $sql=" img='".$nouveauChemin."'";

            }
        
        }

        $sql=rtrim($sql, ',');


        if($connexion->query($sql)){
            $userIP = $_SERVER['REMOTE_ADDR'];

            logUserAction($connexion,$_SESSION['id_user'],"modification d'un compte",date("Y-m-d H:i:s"),$userIP,"valeur enregistrée : $login+$mdp ");
    
            header("location: compte?success=Modification avec success");
      
        }else{

            header("location: compte?erreur=$connexion->error");
        }
    
        }


      
?>