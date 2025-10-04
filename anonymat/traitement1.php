<?php 

include '../php/connexion.php';
include '../php/lib.php';
session_start();

 

if(isset($_POST['etudiant']) and isset($_POST['ecue']) and isset($_POST['classe']) and isset($_POST['semestre']) and isset($_POST['examen'])
        and isset($_POST['annee'])){
    


            $etudiant =$_POST['etudiant']; $ecue=$_POST['ecue']; $classe =$_POST['classe']; $semestre =$_POST['semestre'];
            $examen =$_POST['examen']; $annee=$_POST['annee'];
            
           if( !estInscritDansClasse($etudiant,$classe,$annee,$connexion))
           {

               header("location: index?erreur=L'etudiant n'est pas inscrit ");
              exit;
           }


           if( !verifierEcueClasse($ecue,$classe,$_SESSION['etablissement'],$connexion))
           {

               header("location: index?erreur=L' ECUE $ecue n'est pas enseignée dans cette classe $classse");
              exit;
           }
           
         
           
             if( ! verifierEcueClasseSemestre($ecue,$classe,$semestre,$_SESSION['etablissement'],$connexion))
           {

               header("location: index?erreur=L' ECUE $ecue n'est pas enseignée dans ce  semestre pour la classe $classe");
              exit;
           }
           
          



           if(isset($_SESSION["classe"])){


                    if($_SESSION["classe"] !== ""){


                        if($_SESSION["classe"] !== $classe){

                            header("location: index?erreur=Vous n'avez pas été attribué cette classe");
                            exit;
                        }
                    }
           }

           if(isset($_SESSION["ecue"])){


            if($_SESSION["ecue"] !== ""){


                if($_SESSION["ecue"] !== $ecue){

                    header("location: index?erreur=ERREUR : Vous n'avez pas été attribué cette ECUE");
                    exit;
                }
            }
   }


          $code =genererCodeAnonymat();
          $agent =$_SESSION['nom_user'];




          $sql ="INSERT INTO anonymat(etudiant,classe,specialite,numero,type,code_ecue,agent,annee,semestre,etab) values($etudiant,'$classe','".recupererSpecialiteParClasse($classe,$connexion)."','$code','$examen','$ecue','$agent','$annee','$semestre','".$_SESSION['etablissement']."')";


         if($connexion->query($sql)){
            $userIP = $_SERVER['REMOTE_ADDR'];

            logUserAction($connexion,$_SESSION['id_user'],"anonymation d'une copie d'examen",date("Y-m-d H:i:s"),$userIP,"valeur enregistrée : $code+$examen+$euce+$annee+$classe");
           

          header("location: index?sucess=Opération effectuée avec success. CODE ANONYMAT : $code");
    
      }else{

          header("location: index?erreur=$connexion->error");
      }


    
    }     



    

?>