<?php 
include '../php/connexion.php';
include '../php/lib.php';

session_start();

if( $_SESSION['id'] == session_id() and  $_SESSION['role']=="gesnote"){


    if(isset($_GET['etudiant']) and isset($_GET['semestre']) and isset($_GET['annee'])){
        $etudiant =$_GET['etudiant'];
        $nom_etudiant = obtenirNomPrenom(getCandidatCodeByInscription($_GET['etudiant'],$connexion),$_GET['annee'],$connexion);
        $semestre =$_GET['semestre'];
      $classe =getClasseByInscription($_GET['etudiant'],$connexion);
      $specialite =getSpecialiteByInscription($etudiant,$connexion);
        $annee=$_GET['annee'];



?>


<!DOCTYPE html>
<html lang="en">

<head>
	
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $_SESSION['univ'];?> - Scolarité de  <?php echo $_SESSION['etablissement'];?> </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../administrateur/<?php echo  $_SESSION['logo_univ']?>">
	<link rel="stylesheet" href="../vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/skin.css">

    <link href="../vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
    
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <?php include "header.php" ;?>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
       <?php 
         include 'nav.html';
       ?>
        <!--**********************************
            Sidebar end
        ***********************************-->
		
		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
				
				<div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h3>Details des resultats</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../scolarite/">Scolarité</a></li>
                            <li class="breadcrumb-item"><a href="inscription">Etudiants</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Relevés</a></li>
                        </ol>
                    </div>
                </div>
			
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

     <div class="col-md-12 text-center">
    <div class="card">
          <div class="card-header">
                                <h4 class="card-title">Details des resultats</h4>
                                <a href="impression_etudiant?etudiant=<?php echo $etudiant ?>&classe=<?php echo $classe?>&semestre=<?php echo $semestre;?>&annee=<?php echo $annee;?>" class="btn btn-primary"> <i class="la la-print"></i></a>
                            </div>
                         
        <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
            <div class="row">
                                            <div class="col-xl-6 text-center">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-username">Etudiant     
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <p  class="alert alert-light"><?php echo  strtoupper($nom_etudiant);?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-username">Specialité     
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <p  class="alert alert-light"><?php echo  strtoupper($specialite);?></p>
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-username">Parcours    
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <p  class="alert alert-light"><?php echo (getParcours($specialite,$connexion));?></p>
                                                    </div>
                                                </div>
                                               
                                            </div>
                                            <div class="col-xl-6">
                                            <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-username">Semestre     
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <p  class="alert alert-light"><?php echo  strtoupper($semestre);?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-username">Classe     
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <p  class="alert alert-light"><?php echo  strtoupper($classe);?></p>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="val-username">Année académique     
                                                    </label>
                                                    <div class="col-lg-6">
                                                        <p  class="alert alert-light"><?php echo  strtoupper($annee);?></p>
                                                    </div>
                                                </div>
                                              
                                            </div>
                                        </div>
                        
        </div>
            
       <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Details des resultats</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                   <table class="table table-bordered">
                                  
    <thead class="table-dark">
        <tr>
              <th scope="col"> N°</th>
        <th scope="col"> Code UE</th>
            <th scope="col">Unité d'enseignement</th>
            <th scope="col">Moy. UE</th>
        
            <th scope="col">ECUE</th>
            <th scope="col">Note sur 20 ( Moyenne Devoir)</th>
            <th scope="col">Note sur 20 ( Moyenne Examen)</th>

            <th scope="col">Crédit</th>
  
        </tr>
    </thead>
    <tbody>
        <?php
      
        // Requête SQL pour récupérer les données des tables UE et ECUE
        $sql_ue = "select distinct(ue.libelle),code from ue  where ue.etab='".$_SESSION["etablissement"]."' and specialite='".$specialite."' and semestre='$semestre'";
         $result_ue = $connexion->query($sql_ue);

        // Boucle sur les UE
        if ($result_ue->num_rows > 0) {
            $count=1;
            while ($row_ue = $result_ue->fetch_assoc()) {
                $ue_nom = $row_ue["libelle"];
                $code_ue=$row_ue["code"];
                // Récupération des ECUE associées à cette UE
                $sql_ecue = "select ecue.code_ecue,ecue.libelle,ecue.credit,vue_repartition.classe,vue_repartition.specialite from ecue join vue_repartition on ecue.libelle=vue_repartition.ecue where ecue.code_ue='$code_ue'  and specialite='$specialite' and classe='$classe'";
                $result_ecue = $connexion->query($sql_ecue);
                $rowspan = $result_ecue->num_rows > 0 ? $result_ecue->num_rows : 1;
                // Affichage de l'UE avec rowspan calculé
                echo "<tr>";
                  echo "<td rowspan='$rowspan'> $count</td>";
                echo "<td rowspan='$rowspan'>" . str_replace("+","'",$code_ue). "</td>";
                echo "<td rowspan='$rowspan'>" . str_replace("+","'",$ue_nom) . "</td>";
                echo "<td rowspan='$rowspan'>" . round(getMoyenneUE($connexion,$etudiant,$semestre,$annee,$code_ue,$_SESSION['etablissement']),2) . "</td>";

             

                // Affichage des ECUE
                if ($result_ecue->num_rows > 0) {
                    while ($row_ecue = $result_ecue->fetch_assoc()) {
                       

                        $notes = round(getNoteDevoir($connexion,$etudiant,$semestre,$annee,$row_ecue["code_ecue"]),2);
                        $notesEx=round(getNoteExamen($connexion,$etudiant,$semestre,$annee,$row_ecue["code_ecue"]),2);
                        echo "<td>" .str_replace("+","'", $row_ecue["libelle"]) . "</td>";

                            echo '<td>'.$notes.'</td>';
                            echo '<td>'.$notesEx.'</td>';
                            echo "<td>" . $row_ecue["credit"] . "</td>";
                          
                         
                            echo "</tr><tr>";
                      
                     
                    }
                   
                      
                      
                } else {
                    echo "<td colspan='4'>Aucune ECUE trouvée</td>";
                    echo "</tr>";
                }
                
                $count++;
            }
        } else {
            echo "<tr><td colspan='4'>Aucune UE trouvée</td></tr>";
        }

     
        ?>
    </tbody>
</table>


                                </div>
                                <p>Année académique :  <b><?php echo $annee;?></b></p>
                            <p> Moyenne du <?php echo $semestre ?> : <b> <i><?php echo calcul_moyenne($etudiant,$semestre,$annee,$_SESSION['etablissement'],$connexion);?></b></i> </p>
                            <p>Decision du jury : <b><i ><?php echo statutSoutenance(calcul_moyenne($etudiant,$semestre,$annee,$_SESSION['etablissement'],$connexion));?></i> </b></p>
                            <p> Mention : <b> <i><?php echo mentionParmoyenne(calcul_moyenne($etudiant,$semestre,$annee,$_SESSION['etablissement'],$connexion));?></b></i> </p>
                            </div>
                        </div>
                    </div>
 </div>
</div>
   



</div>

   
      


<div class="modal" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel"><?php echo $_SESSION['univ'];?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="messageBody">
                <!-- Contenu du message -->
            </div>
        </div>
    </div>
</div>

        <!--**********************************
            Footer start
        ***********************************-->
         <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developpé  par <a href="htpps:/www.cet-up.com" target="_blank">CETUP</a> 2023</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->

                                                        </div>
    
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
         }}

else{
    header("location: ../login");
}?>