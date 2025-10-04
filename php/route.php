<?php


include('connexion.php');
include('lib.php');
session_start();
$userIP = $_SERVER['REMOTE_ADDR'];


    $username=null;
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Requête pour vérifier les informations d'authentification
    $query = "SELECT id,nom,login, role,mdp,etab,univ,img,statut,code_enseignant FROM utilisateur WHERE mdp = ? and email=?";
    $stmt = $connexion->prepare($query);
    $stmt->bind_param("ss", $password,$email);
    $stmt->execute();
    $stmt->bind_result($id,$nom,$userName, $userRole,$mdp,$etablissement,$univ,$img,$st,$code);
    $stmt->fetch();
    $stmt->close();
    if ($id !== null) {
        $statut = getstatut($id,$connexion);
        // Authentification réussie, rediriger en fonction du rôle
       if ($userRole == 'candidat') {
            $_SESSION['id']=session_id();
            $_SESSION['id_user'] = $id;
            $_SESSION['login'] = $userName;
            $_SESSION['role'] = $userRole;
            $_SESSION['mdp'] =$mdp;

            if(idCandidatExiste1($id,$connexion)){
                $_SESSION['statut'] =getStatutCandidat($id,$connexion);
                $_SESSION['code'] = getCodeCandidat($id,$connexion);
                $_SESSION['img'] = getImgCandidat($id,$connexion);
                $_SESSION['annee'] =getAnneeCandidat($id,$connexion);
            }
            logUserAction($connexion,$id,"connexion candidat",date("Y-m-d H:i:s"),$userIP,"valeur :  $id");
            header("Location: ../candidat");
        } else if ($userRole == 'enseignant'){

            $_SESSION['id'] = session_id();
            $_SESSION['id_user'] = $id;
            $_SESSION['login'] = $userName;
            $_SESSION['role'] = $userRole;

            $_SESSION['code_enseignant'] =$code;

            logUserAction($connexion,$id,"connexion enseignant",date("Y-m-d H:i:s"),$userIP,"valeur :  $id");
            if($code == NULL){
                  header("Location: ../enseignant/compte");
            }else{
                 $_SESSION['lib_etab']=getLibelleEtablissement(  $_SESSION['etablissement'],$connexion);
                 $_SESSION['img'] = $img;
                 $_SESSION['etablissement'] =$etablissement;
                 $_SESSION['logo_univ'] = getlogo($id,$connexion);
                 $_SESSION['univ'] ="UDSN";
                  header("Location: ../enseignant");
            }
        }
        else {
        // Authentification échouée, rediriger vers la page de connexion avec un message d'erreur
        header("Location: ../connexion?erreur=Utilisateur inconnu. Essayez à nouveau");
     }
    }
 else {
    // Authentication failed
    header("Location: ../connexion?erreur=Utilisateur inconnu. Essayez à nouveau");
}
