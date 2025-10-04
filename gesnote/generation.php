<?php 
include '../php/connexion.php';
include '../php/lib.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

if( $_SESSION['id'] == session_id() and  $_SESSION['role']=="gesnote"){


    if(isset($_GET["ins_"]))
    {

        $id_etudiant = $_GET["ins_"];

        $annee = getAnneeInscription($connexion,$id_etudiant,$_SESSION["etablissement"]);
        $code_etudiant = getCandidatCodeByInscription($id_etudiant,$connexion);
        $nom_etudiant = getNomEtudiant($code_etudiant,$connexion,$_SESSION["lib_etab"]);
        $prenom_etudiant = getPrenomEtudiant(getCandidatCodeByInscription($id_etudiant,$connexion),$connexion,$_SESSION["lib_etab"]);
        $date_naissance = getDateNaissanceCandidat($code_etudiant,$_SESSION["lib_etab"],$connexion);
        $lieu_naissance = getLieuNaissanceCandidat($code_etudiant,$_SESSION["lib_etab"],$connexion);
        $specialite = getSpecialitetudiant($id_etudiant,$_SESSION["etablissement"],$connexion);
        $niveau = getNiveauEtudiant($id_etudiant,$_SESSION["etablissement"],$connexion);
        $classe = getClasseByInscription($id_etudiant,$connexion);

?>


<!DOCTYPE html>
<html lang="en">

<head>
	
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../administrateur/<?php echo  $_SESSION['logo_univ']?>">
	<link rel="stylesheet" href="../vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/skin.css">

    <link href="../vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
     .underline-text {
        text-decoration: underline;
    }
.table-bold-rows-cols {
border-collapse: collapse;
}

.table-bold-rows-cols tbody tr {
font-weight: bold;
}
.custom-font {
            font-family: "Times New Roman", Times, serif; /* Utilisation de la police Times New Roman, ou Times, ou une police serif si elles ne sont pas disponibles */
        }
.table-bold-rows-cols tbody tr td,
.table-bold-rows-cols tbody tr th {
border: 1px solid black; /* Bordure extérieure */
padding: 8px; /* Ajoute un espacement entre le texte et les bordures */
}

.table-bold-rows-cols tbody tr td:not(:first-child),
.table-bold-rows-cols tbody tr th:not(:first-child) {
border-left: 1px solid black; /* Bordure intérieure gauche */
}

.table-bold-rows-cols tbody tr td:not(:last-child),
.table-bold-rows-cols tbody tr th:not(:last-child) {
border-right: 1px solid black; /* Bordure intérieure droite */
}

body {
    position: relative; 
    font-family: "Times New Roman", Times, serif;/* Permet de positionner le logo par rapport à cette balise */
}

.logo-filigrane {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.2; /* Opacité du logo de filigrane */
    z-index: -1; /* Pour placer le logo derrière le contenu principal */
}


     </style>

</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
        <img src="../images/univ.png" class="logo-filigrane" alt="Logo en filigrane">
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

       
        <div class="content h-50" id="contenu-a-imprimer">
            <!-- row -->
            <div class="container-fluid">
            <div class="row" style="height: 250px;">
    <div class="col-md-12">
      <div class="d-flex justify-content-between align-items-center">
        <!-- Université Denis Sassou Nguesso -->
        <div class="p-2"> <h4 class="justify-content-center custom-font"> <b>UNIVERSITE DENIS SASSOU-N'GUESSO</b> </h4>
            <h5 class="justify-content-center custom-font">DIRECTION DE LA SCOLARITE ET DES EXAMENS</h5>
           <p>SERVICE DE LA SCOLARITE ET DES EXAMENS</p></div>
        <div class="p-2">
          <img src="../images/univ.png" alt="Logo de l'université" style="max-width: 100px;">
        </div>
        <!-- Devise -->
        <div class="p-2">  <h4 class="custom-font">Rigueur-Excellence-Lumieres</h4>
       <p><?php  echo mb_strtoupper($_SESSION["lib_etab"]);?></p>
       <?php 
          if(typeEtablissement($_SESSION["lib_etab"],$connexion) =="faculté"){

                 ?>
                 <p class="justify-content-center custom-font">VICE-DECANAT</p>
                 <?php }?>
</div>
    
        <!-- Logo -->
     
      </div>
    </div>
  </div>
 
				<div class="row  justify-content-center align-items-center">
                    
                   
                       <h1 class="custom-font">RELEVE INDIVIDUEL DE NOTES ET RESULTATS</h1>
                       
                       
                </div>
                <div class="row  justify-content-center align-items-center">
                    
                   
                    <h2 class="custom-font">Année Universitaire : <?php echo $annee;?></h2>
                       
                       
                </div>

                <div class="row  justify-content-center align-items-center">
                    
                   
                    <h2 class="custom-font">LICENCE de  <?php echo getParcours($specialite,$connexion);?></h2>
                       
                       
                </div>
              
                <div class="row  justify-content-center align-items-center">
                    
                   
                    <h2 class="custom-font">SPECIALITE /OPTION :  <?php echo $specialite;?></h2>
                       
                       
                </div>
               
 <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-4">
            
                                     
               <div class="form-group row">
               <label class="col-sm-12 col-form-label custom-font">NOM(s) DE L'ETUDIANT :  <b><?php echo strtoupper($nom_etudiant);?></b></label>
               </div>
               <div class="form-group row">
               <label class="col-sm-12 col-form-label custom-font">PRENOM(s) DE L'ETUDIANT :  <b><?php echo strtoupper($prenom_etudiant);?></b></label>
                  
               </div>
               <div class="form-group row">
               <label class="col-sm-12 col-form-label custom-font"> NUMERO MATRICULE DE L'ETUDIANT :  <b><?php echo strtoupper($code_etudiant);?></b></label>
                   
               </div>
               <div class="form-group row">
                   <label class="col-sm-12 col-form-label custom-font">DATE DE NAISSANCE :  <b><?php echo $date_naissance;?></b></label>
                   
               </div>
               <div class="form-group row">
                   <label class="col-sm-12 col-form-label custom-font">LIEU DE NAISSANCE :  <b><?php echo strtoupper($lieu_naissance);?></b></label>
                  
               </div>
          
              


            </div>

          

                                    
                    
</div>     

<div class="row row h-100 justify-content-center align-items-center" style="height: 500px;">
    <div class="col-sm-12">
      <?php
      
        $sql ="select distinct(semestre) as semestre from ue   where niveau='$niveau' and specialite='$specialite' and etab='".$_SESSION["etablissement"]."'";

        $resultat= $connexion->query($sql);

        while($semestre = $resultat->fetch_object()){
       ?>



<p> <b class="underline-text custom-font">  <?php echo strtoupper($semestre->semestre)?> </b></p>



     
       
        <div class="table-responsive" >
           
            <table  class="table  table-bordered table-responsive-sm table-bold-rows-cols custom-font">
              
                    <tr>
                        <th>Code UE</th>
                        <th>Unité d'enseignement</th>
                        <th>Session</th>
                    
                        <th>Note</th>
                      
                      
                     
                    </tr>
               
                <tbody>
                    <?php 
                       $sql="select ue.libelle,ue.code from ue join vue_repartition on ue.code=vue_repartition.ue where classe='$classe' and ue.niveau='$niveau' and 
                       vue_repartition.semestre='".$semestre->semestre."'  and ue.etab='".$_SESSION["etablissement"]."'";
                       $requete=$connexion->query($sql);
                       while($data = $requete->fetch_object()){
                           
                     $ecues = rechercher_notes_eliminatoires($id_etudiant,$semestre->semestre,$annee,$connexion);
                    ?>
                    <tr>
                        <th><?php echo $data->code;?></th>
                        <td><?php echo str_replace("+","'",$data->libelle);?></td>
                        <td><?php echo ($ecues == null )?   "" :   "rattrapage";?></td>  
                        <td><?php $m=  getMoyenneUE($connexion,$id_etudiant,$semestre->semestre,$annee,$data->code,$_SESSION["etablissement"]); echo $m;?></td>
                       
                       
                      
                      </tr>
                    <?php }?>
                    <tr>
                      
                        <td  > Décision du jury : <?php echo statutSoutenance(calcul_moyenne($id_etudiant,$semestre->semestre,$annee,$_SESSION["etablissement"],$connexion)) ;?> </td>
                        <td colspan="2"> Moyenne du <?php echo $semestre->semestre; ?> : <b><?php ?><?php echo calcul_moyenne($id_etudiant,$semestre->semestre,$annee,$_SESSION["etablissement"],$connexion);?> </td>
                        <td> Mention :  <?php echo mentionParmoyenne(calcul_moyenne($id_etudiant,$semestre->semestre,$annee,$_SESSION["etablissement"],$connexion))?> </td>
                    </tr>
                  
                </tbody>
            </table>
       
  
       </div>
  
    <?php }?>
    </div>

</div>

<div class="row h-100 justify-content-center custom-font">
    <div class="col-sm-8">
    <div class="float-right" >
    <h4 style="height: 50px">Fait à Kintélé le ...........................................................................</h4>
             <h4 style="height: 50px">Le Directeur de la Scolarité et des Examens</h4>
             <h3>Cyr Jonas MORABANDZA</h3>
       

    </div>
  
    </div>
    

</div>



</div> 
        </div>
        </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
       
        <!--**********************************
            Footer start
        ***********************************-->
       
      

    
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="../vendor/global/global.min.js"></script>
	<script src="../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="../js/custom.min.js"></script>
		
    <!-- Chart Morris plugin files -->
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morris/morris.min.js"></script>
		
    <script src="../vendor/select2/js/select2.full.min.js"></script>
    <script src="../js/plugins-init/select2-init.js"></script>
	<!-- Chart piety plugin files -->
    <script src="../vendor/peity/jquery.peity.min.js"></script>
	
		<!-- Demo scripts -->
    <script src="../js/dashboard/dashboard-2.js"></script>
	
	<!-- Svganimation scripts -->
    <script src="../vendor/svganimation/vivus.min.js"></script>
    <script src="../vendor/svganimation/svg.animation.js"></script>
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../js/plugins-init/datatables.init.js"></script>

    <script>
    $(document).ready(function() {
        // Vérifier si les attributs "erreur" ou "success" sont présents dans l'URL
        var urlParams = new URLSearchParams(window.location.search);
        var erreur = urlParams.get('erreur');
        var success = urlParams.get('sucess');

        // Afficher le modal si l'un des attributs est présent
        if (erreur || success) {
            var message = erreur ? "Erreur : " + erreur : "Message : " + success;
            $('#messageBody').text(message);
            $('#messageModal').modal('show');

            // Effacer les attributs de l'URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>

<script>
$(document).ready(function() {
  // Déclencher l'impression une fois que le document est prêt
  window.print();
});
</script>
<script>
    $(document).ready(function() {
        // Gérer le clic sur le bouton "Modifier"
        $('.btn-primary').click(function() {
            // Récupérer les données de la ligne cliquée
            var id = $(this).data('id');
            var x = $(this).data('moydev');
            var y= $(this).data('moyex');
 
           

            // Pré-remplir le formulaire modal avec les données actuelles
            $('#noteId').val(id);
            $('#nouveauMoyDev').val(x);
            $('#nouveauMoyEx').val(y);

        });
    });
</script>

	
</body>
</html>

<?php 

    }
}else{
    header("location: ../login");
}?>