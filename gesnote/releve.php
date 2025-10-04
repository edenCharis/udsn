<?php 
include '../php/connexion.php';
include '../php/lib.php';

session_start();

if( $_SESSION['id'] == session_id() and  $_SESSION['role']=="gesnote"){


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
                            <h3>Relevés de note</h3>
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
				<div class="row">
                  
					<div class="col-md-12 text-center">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Liste d'admission</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form method="get">

                                        <div class="form-row">
                                           
                                            <div class="form-group col-sm-3">
                                                <label>Classe</label>
                                                <select   class="disabling-options" class="form-control form-control-lg" name="classe" recquired>
                                                <option selected=""></option>
                                               <?php 
                                                        $sql="select * from classe where etab='".$_SESSION['etablissement']."'";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option><?php echo str_replace("+","'",$etablissement['libelle']);?></option>
                                                 <?php }?>
                                            </select>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Specialité</label>
                                                <select   class="disabling-options" class="form-control form-control-lg" name="specialite" recquired>
                                                <option selected=""></option>
                                               <?php 
                                                        $sql="select * from specialite where etab='".$_SESSION['lib_etab']."'";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option><?php echo str_replace("+","'",$etablissement['libelle']);?></option>
                                                 <?php }?>
                                            </select>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label>Semestre</label>
                                                <select   class="disabling-options" class="form-control form-control-lg" name="semestre" required >
                                                <option selected=""></option>
                                               <?php 
                                                        $sql="select * from semestre ";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option><?php echo str_replace("+","'",$etablissement['libelle']);?></option>
                                                 <?php }?>
                                            </select>
                                            </div>
                                            <div class="form-group col-sm-3">
                                            <label>Année académique</label>
                                            <select  class="disabling-options" class="form-control form-control-lg" name="annee" recquired>

                                                <option selected=""></option>
                                               <?php 
                                                        $sql="select * from annee ";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option><?php echo str_replace("+","'",$etablissement['libelle']);?></option>
                                                 <?php }?>
                                            </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="generer"><i class="la la-cog"></i>GENERER</button>
                                    </form>
                                   
                                </div>
                            </div>
                        </div>
					</div>


                    <div class="col-md-12 text-center">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Relevé</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form method="get">

                                        <div class="form-row">
                                           
                                            <div class="form-group col-sm-4">
                                                <label>Etudiant</label>
                                                <select   class="disabling-options" class="form-control form-control-lg" name="etudiant" recquired>
                                                <option selected=""></option>
                                               <?php 
                                                        $sql="select * from inscription where etab='".$_SESSION['etablissement']."'";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option value="<?php echo $etablissement["candidat"];?>"><?php echo str_replace("+","'",obtenirNomPrenom($etablissement["candidat"],$etablissement["annee"],$connexion));?></option>
                                                 <?php }?>
                                            </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label>Classe</label>
                                                <select   class="disabling-options" class="form-control form-control-lg" name="classe" recquired>
                                                <option selected=""></option>
                                               <?php 
                                                        $sql="select * from classe where etab='".$_SESSION['etablissement']."'";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option><?php echo str_replace("+","'",$etablissement['libelle']);?></option>
                                                 <?php }?>
                                            </select>
                                            </div>
                                           
                                            </div>
                                            <div class="form-group col-sm-4">
                                            <label>Année académique</label>
                                            <select  class="disabling-options" class="form-control form-control-lg" name="annee" recquired>

                                                <option selected=""></option>
                                               <?php 
                                                        $sql="select * from annee ";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option><?php echo str_replace("+","'",$etablissement['libelle']);?></option>
                                                 <?php }?>
                                            </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success" name="generer1"><i class="la la-cog"></i>GENERER</button>
                                    </form>
                                   
                                </div>
                            </div>
                        </div>
					</div>
					
				
					
                </div>
				<?php 
                       if(isset($_GET['generer'])){

                        if(isset($_GET['classe']) and isset($_GET['semestre']) and isset($_GET['specialite']) and isset($_GET['annee']))
                        {


                           
                ?>
                   <div class="col-lg-12">

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo $_GET['specialite'];?>-<?php echo $_GET['semestre'];?>-<?php echo $_GET['annee'];?></h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>Matricule</th>
                                                <th>Etudiant</th>
                                                <th>Classe</th>
                                            
                                                <th>Moy. du <?php echo $_GET['semestre'];?></th>
                                                <th>Observation</th>
                                                <th>Année académique</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                               $sql="select * from inscription where classe='".$_GET['classe']."' and annee='".$_GET['annee']."'";
                                               $requete=$connexion->query($sql);
                                               while($data = $requete->fetch_object()){
                                                   
                                                   $statut="";

                                                $moyenne = calcul_moyenne($data->id,$_GET['semestre'],$_GET['annee'],$_SESSION['etablissement'],$connexion);
                                               ($moyenne !== "-") ?   $statut = statutSoutenance($moyenne) : "";
                                                $ecues = rechercher_notes_eliminatoires($data->id,$_GET['semestre'],$_GET['annee'],$connexion);
                                                $_SESSION['ecue_non_valide'] =  $ecues;

                                            
                                            ?>
                                            <tr>
                                                <th><?php echo getCandidatCodeByInscription($data->id,$connexion);?></th>
                                                <td><?php echo obtenirNomPrenom(getCandidatCodeByInscription($data->id,$connexion),$_GET['annee'],$connexion);?></td>
                                                <td><?php echo $_GET['classe'];?></td>
                                             
                                                <td><?php echo $moyenne;?></td>
                                                <td><?php echo ( $statut != "") ? '<span class="badge badge-info">'.$statut : ""; ?>
                                                </span> <?php echo ($ecues == null )?   "" :   "passe en rattrapage";

                                                   ?>
                                                   
												
                                                </td>
                                                <td>
                                                <?php echo $_GET['annee'];?>

                                                </td>
                        
                                                <td class="color-primary"> <a href="releve-etudiant?etudiant=<?php echo $data->id;?>&annee=<?php echo $_GET['annee'];?>&semestre=<?php echo $_GET['semestre'];?>" class="btn btn-success" name="generer"><i class="la la-list"></i>Relevé individuel</a>
                                                <a href="releve-classe?classe=<?php echo $_GET["classe"];?>&annee=<?php echo $_GET['annee'];?>&semestre=<?php echo $_GET['semestre'];?>" class="btn btn-warning" name="generer"><i class="la la-list"></i>Relevé collectif</a></td>
                                            </tr>
                                            <?php }?>
                                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <?php } }?>
                  <?php 
                       if(isset($_GET['generer1'])){

                        if(isset($_GET['etudiant']) and isset($_GET['classe']) and  isset($_GET['annee']))
                        {


                            if(verifierInscription($_GET["etudiant"],$_GET["annee"],$connexion)){
                ?>

<div class="col-lg-12">

<div class="card">
    <div class="card-header">
      
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Etudiant</th>
                        <th>Date de naissance</th>
                    
                        <th>Lieu de naissance</th>
                        <th>Specialité</th>
                        <th>Niveau</th>
                        <th>Année Universitaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                       $sql="select * from inscription where classe='".$_GET['classe']."' and annee='".$_GET['annee']."' and candidat='".$_GET["etudiant"]."'";
                       $requete=$connexion->query($sql);
                       while($data = $requete->fetch_object()){

                       

                    
                    ?>
                    <tr>
                        <th><?php echo $data->candidat;?></th>
                        <td><?php echo obtenirNomPrenom(getCandidatCodeByInscription($data->id,$connexion),$data->annee,$connexion);?></td>
                        <td><?php echo getDateNaissanceCandidat($data->candidat,$_SESSION["lib_etab"],$connexion);?></td>  
                        <td><?php echo getLieuNaissanceCandidat($data->candidat,$_SESSION["lib_etab"],$connexion);?></td>
                        <td><?php echo getSpecialitetudiant($data->id,$_SESSION["etablissement"],$connexion);?></td>
                        <td><?php echo getNiveauEtudiant($data->id,$_SESSION["etablissement"],$connexion);?></td>
                        <td>
                           <?php echo $data->annee;?>
                        </td>
                  
                        <td class="color-primary"> <a href="generation?ins_=<?php echo $data->id;?>"  class="btn btn-info" name="generer"><i class="la la-list"></i>Details</a>
                      </tr>
                    <?php }?>
                  
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
                <?php }else{



        echo '
        <div class="col-lg-12">
        
       
          <div class="card">
          <div class="card-body">

          <p > "'. mb_strtoupper('L\' etudiant n \'e st pas retrouvé"').'"</div></div></p>
          </div>
          </div>
       
        </div>';

                }} }?>


        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        <div class="modal" id="modifierModal" tabindex="-1" role="dialog" aria-labelledby="modifierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifierModalLabel">Ecues non validés</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de modification -->
                <?php 
                  if(isset($_SESSION['ecue_non_valide'])){
                    $t = array();
                    $t = $_SESSION['ecue_non_valide'];
                ?>
               <p>

                 <?php 
                    foreach ( $t as $e){
                        echo $e. " <br>"; }
                 
                 ?>
                 
               </p>
               <?php }?>
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


}else{
    header("location: ../login");
}?>