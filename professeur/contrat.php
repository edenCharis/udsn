<?php 
include '../php/connexion.php';
include '../php/lib.php';

session_start();

if($_SESSION['id']== session_id() and  $_SESSION['role']=="enseignant"){
    
    $annee=$_SESSION["annee"];


  

?>


<!DOCTYPE html>
<html lang="en">

<head>
	
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>  <?php echo $_SESSION['etablissement'];?> </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../administrateur/<?php echo  $_SESSION['logo_univ']?>">
	<link rel="stylesheet" href="../vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="../vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/skin.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">


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
         include 'nav.php';
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
                    <div class="col-sm-8 p-md-0">
                        <div class="welcome-text">
                            <h4>DETAILS D'UN CONTRAT D'ENSEIGNEMENT </h4>
                        </div>
                    </div>
                 
                </div>

              <?php 
              
                 $numero_contrat =getContratEnseignant($_SESSION["code_enseignant"],$connexion);
                 $annee_academique =$_SESSION["annee"];


                 $sql="select * from enseignant where id in ( select enseignant from contrat where numero_contrat='".$numero_contrat."' and annee='".$annee_academique."')";

                 $requete=$connexion->query($sql);

                 $data = $requete->fetch_object();
              ?>
               <form action="traitement1.php" method="post">
                <div class="row">
					<div class="col-xl-12 col-xxl-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
								<h5 class="card-title">INFORMATIONS PERSONNELLES DE L'ENSEIGNANT</h5>
							</div>
							<div class="card-body">
                             
									<div class="row">
									    	<div class="col-lg-6 col-md-6 col-sm-12">
									    	    <div class="form-group">
											
												<input type="hidden" class="form-control text-danger"  name="id" value="<?php echo $data->id;?>">
											</div>
											<div class="form-group">
												<label class="form-label">Code Unique</label>
												<input type="text" class="form-control text-danger"  name="code" value="<?php echo $data->code;?>" disabled>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Nom(s)</label>
												<input type="text" class="form-control text-danger"  name="nom" value="<?php echo $data->nom;?>" disabled>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Prénom(s)</label>
												<input type="text" class="form-control text-danger" name="prenom" value="<?php echo $data->prenom;?>" disabled>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Date de naissance</label>
												<input type="date" class="form-control text-danger" name="date_naissance" value="<?php echo $data->date_naissance;?>" disabled>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Diplôme</label>
                                                <select id="diplome"  class="disabling-options text-danger"  disabled name="diplome" required>
                                                <option selected=""><?php echo $data->diplome;?></option>
                                               <?php 
                                                        $sql="select * from type_diplome ";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option><?php echo str_replace("+","'",$etablissement['libelle']);?></option>
                                                 <?php }?>
                                            </select></div>
										</div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Spécialité</label>
												<input type="text" class="form-control text-danger" name="specialite" disabled value=" <?php echo str_replace("+","'",$data->specialite);?>">
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Grade</label>
												<select id="grade"  class="disabling-options" class="text-danger" name="grade" disabled required>
                                                <option selected=""><?php echo str_replace("+","'",$data->grade);?></option>
                                               <?php 
                                                        $sql="select * from type_grade ";
                                                        $resultat=$connexion->query($sql);
                                                        while($etablissement =$resultat->fetch_assoc()){
                                                ?>
                                                 <option><?php echo str_replace("+","'",$etablissement['libelle']);?></option>
                                                 <?php }?>
                                            </select>
											</div>
										</div>
									
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Contact téléphonique</label>
												<input type="text" class="form-control text-danger" name="telephone" value="<?php echo $data->telephone;?>" disabled>
											</div>
										</div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Email</label>
												<input type="email" class="form-control text-danger"  name="email" value="<?php echo $data->email;?>" disabled>
											</div>
										</div>
                                        <div class="col-lg-6 col-md-6 col-sm-12" disabled>
											<div class="form-group">
												<label class="form-label">Lieu de résidence</label>
												<input type="text" class="form-control text-danger"  name="ville" value="<?php echo $data->ville;?>">
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="form-group">
												<label class="form-label">Sexe</label>
												<select class="form-control text-danger" name="sexe" disabled>
													<option selected=""><?php echo $data->sexe;?></option>
													<option value="Homme" >Homme</option>
													<option value="Femme">Femme</option>
												</select>
											</div>
										</div>
                                     
                                    
									
									
									</div>
								
                            </div>
                        </div>
                      
                    </div>
                

										

		  
				</div>
                </form>
                <div class="row">
					<div class="col-xl-12 col-xxl-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
								<h5 class="card-title">DISPOSITIONS PARTICULIERES</h5>
							
							</div>
							<div class="card-body">
                              <p >Etablissement: <h3><?php echo mettrePremieresLettresMajuscules(getLibelleEtablissement($data->etab,$connexion));?></h3> </p> 
                              <p>Année académique: <h3><?php echo $annee_academique;?></h3> </p>  
                              <p>Grade: <h3><?php echo str_replace("+","'",getGradeById($data->id,$connexion));?></h3></p>
                              <p>Ecues: <h4><?php $ecues=getEcuesContrat($numero_contrat,$data->etab,$connexion); foreach($ecues as $e){
                                echo getecue($e,$connexion).'    '.'</br>';
                              } ?></h4> </p>   
                             

                            </div>
                            
                            
                             
                           
                          </div>
                        
                    </div>
				</div>
              
           
            </div>

            
        </div>
        
        <!-- Modal -->
<div class="modal fade" id="ecueModal" tabindex="-1" aria-labelledby="ecueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ecueModalLabel">Ajouter un ECUE</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  method="POST" action="traitement4">
                    <div class="mb-3">
                        <label for="ecueSelect" class="form-label">Choisissez un ECUE :</label>
                        
                        <select multiple class="disabling-options" class="form-control form-control-lg" id="sel2"  name="ecues[]">
                               <option value=""></option>
                               <?php 
                                     $sql="select * from ecue ";
                                     $resultat=$connexion->query($sql);
                                     while($etablissement =$resultat->fetch_assoc()){
                             ?>
                              <option  value="<?php echo $etablissement['code_ecue'];?>"><?php echo str_replace("+","'", $etablissement["libelle"])."(".$etablissement["code_ecue"].")" ?></option>
                              <?php }?>
                             
                           </select>
                    </div>
                    	<div class="mb-3">
												<label class="form-label">Matricule Enseignant</label>
												<input type="text" class="form-control text-danger"  name="code" value="<?php echo $data->code;?>">
											</div>
												<div class="mb-3">
												<label class="form-label">Numéro contrat</label>
												<input type="text" class="form-control text-danger"  name="contrat" value="<?php echo $data->contrat;?>" >
											</div>
												<div class="mb-3">
												<label class="form-label">Année académique</label>
												<input type="text" class="form-control text-danger"  name="annee" value="<?php echo $annee_academique;?>" >
											</div>
												<div class="mb-3">
												<label class="form-label">Etablissement</label>
												<input type="text" class="form-control text-danger"  name="etab" value="<?php echo $data->etab;?>" >
											</div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"> <i class="fa fa-times"></i> Fermer</button>
                <button type="submit" class="btn btn-sm btn-success"> <i class="fa fa-plus"></i> Ajouter</button>
            </div>
             </form>
        </div>
    </div>
</div>

        <!--**********************************
            Content body end
        ***********************************-->


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