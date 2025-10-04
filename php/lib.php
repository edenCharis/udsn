<?php 


function getECUEsByClassSemesterYear($classe, $semestre, $annee,$etab, $pdo) {
    try {
        // Préparer la requête SQL
        $sql = "SELECT  r.code_ecue,e.VHG as vhg,r.nom,r.prenom
                FROM  repartition_enseignant as r  JOIN ecue as e  on r.code_ecue=e.code_ecue
                WHERE r.classe = :classe
                  AND r.semestre = :semestre
                  AND r.annee = :annee
                  AND r.etab =:etab";
        
        // Préparer la déclaration
        $stmt = $pdo->prepare($sql);
        
        // Lier les paramètres
        $stmt->bindParam(':classe', $classe, PDO::PARAM_STR);
        $stmt->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $stmt->bindParam(':annee', $annee, PDO::PARAM_STR);
        $stmt->bindParam(':etab', $etab, PDO::PARAM_STR);
        // Exécuter la requête
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);

    } catch (PDOException $e) {
        // Gérer les erreurs
        die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
    }
}

function getCompletedHours($code_ecue,$classe, $semestre,$annee,$etab, $pdo) {
    try {
       
        $sql = "SELECT SUM(heure) AS total_heures
                FROM cours
                WHERE code_ecue = :code_ecue
                AND classe = :classe
                AND semestre = :semestre
                AND annee = :annee
                AND etab= :etab";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':code_ecue' => $code_ecue, ':classe' => $classe, ':semestre' => $semestre, ':annee' => $annee, ':etab' => $etab]);
        

       $h=  $stmt->fetchAll(PDO::FETCH_OBJ);
       $heures =0;
       foreach ($h as $t)
       {
           $heure =$t->total_heures;
       }
       
       return $heure;

    } catch (PDOException $e) {
        // Gérer les erreurs
        error_log("Erreur PDO : " . $e->getMessage());
        return 0;
    }
}




function NiveauParSemestre($semestre){

    if($semestre == "semestre 1" OR $semestre == "semestre 2")
    {
        return "Première année";
    }else if($semestre == "semestre 3" OR $semestre == "semestre 4"){
        return "Deuxième année";
    }else{
        return "Troisième année";
    }
}
function is_login_used($login,$connexion) {
   

    // Préparation de la requête SQL pour éviter les injections SQL
    $stmt = $connexion->prepare("SELECT COUNT(*) FROM utilisateur WHERE login = ?");
    $stmt->bind_param("s", $login);
    
    // Exécution de la requête
    $stmt->execute();
    
    // Récupération du résultat
    $stmt->bind_result($count);
    $stmt->fetch();
    
  

    // Si le nombre d'occurrences est supérieur à 0, l'email est déjà utilisé
    return $count > 0;
}


function emailExiste($connexion, $email) {

    $query = "SELECT id FROM enseignant WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // Vérification du résultat
    $existe = $stmt->num_rows > 0;

    // Fermeture de la requête
    $stmt->close();

    return $existe;
}

function genererMatricule($anneeInscription, $faculteCode, $specialite, $numeroEtudiant) {
   
    
    
    $annee = substr($anneeInscription, -3);
  
    $programmeCode = substr($specialite, -3);
    
   
    $numeroEtudiant = str_pad($numeroEtudiant, 5, '0', STR_PAD_LEFT);
    
 
    $matricule = "{$annee}-{$faculteCode}{$programmeCode}-{$numeroEtudiant}";
    
    return $matricule;
}
function is_email_used($email,$connexion) {
   

    // Préparation de la requête SQL pour éviter les injections SQL
    $stmt = $connexion->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = ?");
    $stmt->bind_param("s", $email);
    
    // Exécution de la requête
    $stmt->execute();
    
    // Récupération du résultat
    $stmt->bind_result($count);
    $stmt->fetch();
    
  

    // Si le nombre d'occurrences est supérieur à 0, l'email est déjà utilisé
    return $count > 0;
}



function reduireTaillePolice($chaine, $taille) {
    return "<span style='font-size: {$taille}px;'>$chaine</span>";
}
function getSignatureData0($conn,$id) {
   
    $sql = "SELECT signature FROM sign WHERE type='$id'  ";

    // Exécuter la requête SQL
    $result = mysqli_query($conn, $sql);

    // Vérifier s'il y a des résultats
    if ($result && mysqli_num_rows($result) > 0) {
        // Récupérer les données de signature
        $row = mysqli_fetch_assoc($result);
        $signatureData = $row['signature'];
        
        // Retourner les données de signature
        return $signatureData;
    } else {
        // Retourner une chaîne vide si aucune signature n'est trouvée
        return "";
    }
}
function getparcoursbycontrat($contrat, $connexion) {
   
  
  

    // Préparer la requête SQL pour récupérer les données de signature
    $sql = "SELECT p.libelle as p FROM contrat_couverture c join ecue e join ue u join specialite s join parcours p on (c.ecue=e.code_ecue and e.code_ue=u.code) and ( u.Specialite=s.libelle and s.parcours=p.libelle) where c.contrat='$contrat'";

    // Exécuter la requête SQL
    $result = mysqli_query($connexion, $sql);

   
    if ($result && mysqli_num_rows($result) > 0) {
        // Récupérer les données de signature
        $row = mysqli_fetch_assoc($result);
        $p = $row['p'];
        
        // Retourner les données de signature
        return $p;
    } else {
        // Retourner une chaîne vide si aucune signature n'est trouvée
        return "";
    }}

function getSignatureData($conn,$type, $etab) {
    // Échapper les variables pour éviter les attaques par injection SQL
  
    $id = mysqli_real_escape_string($conn, $type);

    // Préparer la requête SQL pour récupérer les données de signature
    $sql = "SELECT signature FROM sign WHERE etab='$etab' and type='$type' ";

    // Exécuter la requête SQL
    $result = mysqli_query($conn, $sql);

    // Vérifier s'il y a des résultats
    if ($result && mysqli_num_rows($result) > 0) {
        // Récupérer les données de signature
        $row = mysqli_fetch_assoc($result);
        $signatureData = $row['signature'];
        
        // Retourner les données de signature
        return $signatureData;
    } else {
        // Retourner une chaîne vide si aucune signature n'est trouvée
        return "";
    }
}

function getNum($type,$connexion,$etab){
    $sql = "SELECT signataire FROM sign WHERE etab='$etab' and type='$type' ";

    // Exécuter la requête SQL
    $result = mysqli_query($connexion, $sql);

    // Vérifier s'il y a des résultats
    if ($result && mysqli_num_rows($result) > 0) {
        // Récupérer les données de signature
        $row = mysqli_fetch_assoc($result);
        $signatureData = $row['signataire'];
        
        return $signatureData;
    } else {
       
        return "";
    }
}

function calculateHourDifference($debut, $fin) {
    // Fonction pour convertir le format "10h30" en "10:30"
   
    try {
        // Convertir les heures au format standard
       
        
        // Créer les objets DateTime avec une date fixe
        $dateTimeDebut = new DateTime('2024-01-01 ' . $debut);
        $dateTimeFin = new DateTime('2024-01-01 ' . $fin);
        
        // Si l'heure de fin est antérieure à l'heure de début, 
        // cela signifie qu'elle est le jour suivant
        if ($dateTimeFin < $dateTimeDebut) {
            $dateTimeFin->add(new DateInterval('P1D'));
        }
        
        // Calculer la différence en secondes puis convertir en heures
        $differenceEnSecondes = $dateTimeFin->getTimestamp() - $dateTimeDebut->getTimestamp();
        $totalHeures = $differenceEnSecondes / 3600;
        
        return $totalHeures;
        
    } catch (Exception $e) {
        return false; // En cas d'erreur de format
    }
}



function getecue($type,$connexion){
    $sql = "SELECT libelle FROM ecue WHERE code_ecue='$type' ";

    // Exécuter la requête SQL
    $result = mysqli_query($connexion, $sql);

    // Vérifier s'il y a des résultats
    if ($result && mysqli_num_rows($result) > 0) {
        // Récupérer les données de signature
        $row = mysqli_fetch_assoc($result);
        $ecue = $row['libelle'];
        
        return $ecue;
    } else {
       
        return "";
    }
}

function getSignataire($id,$etablissement,$connexion)
{
    $sql="select * from enseignant where etab='$etablissement' and id=$id)";
    $resultat= $connexion->query($sql);

    if ($resultat->num_rows > 0) {

        while($row = mysqli_fetch_assoc($resultat)){

            echo   ($row["grade"] == "Aucun" ? ($row["sexe"]== "masculin" ? "Monsieur"." ".$row["nom"]." ".$row["prenom"] : "Madame". " ".$row["nom"]." ".$row["prenom"] ) : $row["grade"]." ".$row["nom"]." ".$row["prenom"])."</br>";  
        }
       
        

        
         } else {
       
        echo  "false";
    }

}





function verifierRoles($numero, $etablissement, $connexion) {
   
   
   
    
    $t=getLibelleEtablissement($etablissement,$connexion);
   
    
    $requete = "SELECT libellle FROM parcours WHERE etab ='$t'";
    
    $statement = $connexion->query($requete);
  
    
    
    // Initialisation des parcours incomplets
    $parcoursIncomplets = array();


    
    
 
    while ($row = $statement->fetch_assoc()) {
        $parcours = $row['libelle'];
        
        // Préparation de la requête pour vérifier les rôles pour ce parcours
        $requeteRoles = "SELECT COUNT(CASE WHEN role = 'Président' THEN 1 END) as president,
                                COUNT(CASE WHEN role = 'Rapporteur' THEN 1 END) as rapporteur,
                                COUNT(CASE WHEN role = 'Membre' THEN 1 END) as membre
                        FROM jdp 
                        WHERE deliberation = ? AND etab = ? AND parcours = ?";
        
        // Préparation de la déclaration
        $statementRoles = $connexion->prepare($requeteRoles);
        
        // Liaison des valeurs aux paramètres de la déclaration
        $statementRoles->bind_param("sss", $numero, $etablissement, $parcours);
        
        // Exécution de la requête
        $statementRoles->execute();
        
        // Récupération des résultats
        $resultatRoles = $statementRoles->get_result();
        $rowRoles = $resultatRoles->fetch_assoc();
        
        // Vérification des résultats
        if ($rowRoles['president'] != 1 || $rowRoles['rapporteur'] != 1 || $rowRoles['membre'] < 4) {
            $parcoursIncomplets[] = $parcours;
        }
    }
    
    return $parcoursIncomplets;
}

function getPerson($numero,$parcours,$role,$connexion,$etablissement){


    $sql="select * from enseignant where   etab='$etablissement' and id in ( select membre from jdp where deliberation='$numero' and parcours='$parcours' and role='$role')";
    $resultat= $connexion->query($sql);

    if ($resultat->num_rows >= 1) {

        while($row = mysqli_fetch_assoc($resultat)){

            echo   ($row["grade"] == "Aucun" ? ($row["sexe"]== "masculin" ? "Monsieur"." ".$row["nom"]." ".$row["prenom"] : "Madame". " ".$row["nom"]." ".$row["prenom"] ) : $row["grade"]." ".$row["nom"]." ".$row["prenom"])."</br>";  
        }
       
        

        
         } else {
        // Gérer les erreurs de requête
        echo "";
        return false;
    }


}

function ajouterTrait($chaine) {
    // Calculer la longueur de la chaîne
    $longueur = strlen($chaine);

    // Calculer la longueur de la ligne de traits
    $longueurTrait = max(0, $longueur - 4);

    // Générer la ligne de traits
    $trait = str_repeat("*", $longueurTrait);

    // Ajouter la chaîne originale suivie du trait
    return $chaine . "\n\n" . $trait;
}

function mettrePremieresLettresMajuscules($phrase) {
    // Mettre la phrase en minuscules
    $phrase = strtolower($phrase);
    
    // Utiliser ucwords() pour mettre la première lettre de chaque mot en majuscules
    $phrase = ucwords($phrase);
    
    return $phrase;
}






function getNomUtilisateurParId($connexion, $userId) {
    $userName = null;
    // Préparer la requête SQL paramétrée
    $query = "SELECT nom FROM utilisateur WHERE id = ?";
    
    // Préparer la déclaration
    $stmt = $connexion->prepare($query);

    // Vérifier si la préparation a échoué
    if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $connexion->error);
    }

    // Lier le paramètre à la déclaration
    $stmt->bind_param("i", $userId);

    // Exécuter la requête
    $stmt->execute();

    // Lier le résultat de la requête à une variable
    $stmt->bind_result($userName);

    // Récupérer la valeur
    $stmt->fetch();

    // Fermer la déclaration
    $stmt->close();

    // Retourner la valeur
    return $userName;
}




function verifierInscription($code,$annee,$connexion){
    $sql ="select COUNT(*) AS nombreInscriptions  from inscription where candidat='$code' and annee='$annee' ";
    $resultat = $connexion->query($sql);
    if ($resultat) {
        // Récupérer le résultat de la requête
        $row = mysqli_fetch_assoc($resultat);

        // Vérifier si le candidat est inscrit (nombre d'inscriptions > 0)
        return $row['nombreInscriptions'] > 0;
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
        return false;
    }

}

function verifierCode($code,$connexion){


   // Échapper le code pour éviter les injections SQL
    $code = mysqli_real_escape_string($connexion, $code);
    
    // Requête SQL pour rechercher le code
    $sql = "SELECT COUNT(*) AS nombre FROM enseignant WHERE code = '$code'";
    
    // Exécution de la requête
    $resultat = $connexion->query($sql);
    
    if ($resultat && $resultat->num_rows > 0) {
        // Récupération du résultat
        $row = $resultat->fetch_assoc();
        
        // Libération des ressources
        $resultat->free();
        
        // Retourne true si le code existe (nombre > 0)
        return $row['nombre'] > 0;
    } else {
        // En cas d'erreur ou si aucun résultat n'est trouvé
        // Possibilité de journaliser l'erreur ici si nécessaire
        // error_log("Erreur verifierCode: " . $connexion->error);
        return false;
    }

}

function verifierCodeEnseignant($code,$connexion,$etab){


    $sql ="select COUNT(*) AS code  from enseignant  where code='$code' and etab='$etab'";


    $resultat = $connexion->query($sql);
    if ($resultat) {
        // Récupérer le résultat de la requête
        $row = mysqli_fetch_assoc($resultat);

        // Vérifier si le candidat est inscrit (nombre d'inscriptions > 0)
        return $row['code'] > 0;
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
        return false;
    }

}

function verifierSpecialiteParcours($specialite,$parcours,$connexion,$etab){


    $sql ="select COUNT(*) AS nombreInscriptions  from specialite where libelle='$specialite' and parcours='$parcours'and etab='$etab' ";


    $resultat = $connexion->query($sql);
    if ($resultat) {
        // Récupérer le résultat de la requête
        $row = mysqli_fetch_assoc($resultat);

        // Vérifier si le candidat est inscrit (nombre d'inscriptions > 0)
        return $row['nombreInscriptions'] > 0;
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
        return false;
    }

}

function verifierSpecialiteClasse($specialite,$classe,$connexion,$etab){


    $sql ="select COUNT(*) AS nombreInscriptions  from classe where specialite='$specialite' and libelle='$classe'and etab='$etab' ";


    $resultat = $connexion->query($sql);
    if ($resultat) {
        // Récupérer le résultat de la requête
        $row = mysqli_fetch_assoc($resultat);

        // Vérifier si le candidat est inscrit (nombre d'inscriptions > 0)
        return $row['nombreInscriptions'] > 0;
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
        return false;
    }

}
function verifierClasseEtudiant($etudiant,$classe,$annee,$connexion,$etab){


    $sql ="select COUNT(*) AS nombreInscriptions  from inscription where id='$etudiant' and classe='$classe'and annee='$annee' and etab='$etab' ";


    $resultat = $connexion->query($sql);
    if ($resultat) {
        // Récupérer le résultat de la requête
        $row = mysqli_fetch_assoc($resultat);

        // Vérifier si le candidat est inscrit (nombre d'inscriptions > 0)
        return $row['nombreInscriptions'] > 0;
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
        return false;
    }

}
function verifierInscription2($code,$annee,$connexion,$etab){


    $sql ="select COUNT(*) AS nombreInscriptions  from inscription where candidat='$code' and annee='$annee'and etab='$etab' ";


    $resultat = $connexion->query($sql);
    if ($resultat) {
        // Récupérer le résultat de la requête
        $row = mysqli_fetch_assoc($resultat);

        // Vérifier si le candidat est inscrit (nombre d'inscriptions > 0)
        return $row['nombreInscriptions'] > 0;
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
        return false;
    }

}



function getTypeSpecialite($connexion, $specialite) {
   
    $sql = "SELECT type FROM specialite WHERE libelle = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("s", $specialite);

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $result = $stmt->get_result();

    // Vérification s'il y a des résultats
    if ($result->num_rows > 0) {
        // Récupération de la première ligne de résultat
        $row = $result->fetch_assoc();
        // Renvoi de la valeur de la colonne "type_specialite"
        return $row["type"];
    } else {
        // Si aucun résultat trouvé, renvoyer un message d'erreur ou une valeur par défaut
        return "Spécialité non trouvée";
    }

    // Fermeture du statement (la connexion n'est pas fermée ici car elle est passée en paramètre)
    $stmt->close();
}

function getThByGrade($connexion, $grade) {
   
  $stmt = $connexion->prepare("SELECT th FROM type_grade WHERE libelle = ?");
    $stmt->bind_param("s", $grade);
    $stmt->execute();
    $stmt->bind_result($th);
    $stmt->fetch();
    $stmt->close();
    return $th;
}

function verifierInscriptionByID($id,$etab,$connexion){


    $sql ="select COUNT(*) AS nombreInscriptions  from inscription where id=$id etab='$etab' ";


    $resultat = $connexion->query($sql);
    if ($resultat) {
        // Récupérer le résultat de la requête
        $row = mysqli_fetch_assoc($resultat);

        // Vérifier si le candidat est inscrit (nombre d'inscriptions > 0)
        return $row['nombreInscriptions'] > 0;
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
        return false;
    }




}

function logUserAction($conn, $userId, $action,$datetime, $ipAddress = null, $additionalInfo = null) {
    // Préparer la requête SQL paramétrée
    $query = "INSERT INTO userlog (UserID, Action, IPAddress, AdditionalInfo,datetime) VALUES (?, ?, ?, ?,?)";

    // Préparer la déclaration
    $stmt = $conn->prepare($query);

    // Vérifier si la préparation a échoué
    if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    // Lier les paramètres à la déclaration
    $stmt->bind_param("issss", $userId, $action, $ipAddress, $additionalInfo,$datetime);

    // Exécuter la requête
    $stmt->execute();

    // Fermer la déclaration
    $stmt->close();
}
function getUnivUtilisateur($id,$connexion){

    $sql ="select univ from utilisateur where id=$id";

    $resultat = $connexion->query($sql);
    if ($resultat) {
        $row = $resultat->fetch_assoc();
        $count = $row['univ'];

    

        return $count ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}



function countEcueForClassAndSemester($classe, $semestre, $conn,$etab) {
    // Préparation de la requête SQL
    $stmt = $conn->prepare("SELECT COUNT(ecue) AS count FROM vue_repartition 
                            INNER JOIN ue ON vue_repartition.libelle_ue = ue.libelle
                            WHERE ue.semestre = ? AND vue_repartition.classe = ? AND ue.etab=?");
    $stmt->bind_param("sss", $semestre, $classe,$etab); // "s" indique que le paramètre est une chaîne de caractères

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $result = $stmt->get_result();
    
    // Récupération du nombre de lignes retournées
    $row = $result->fetch_assoc();
    $count = $row["count"];


    
    // Retourne le nombre d'ECUE pour la classe et le semestre donnés
    return $count;
}


function countEcueForUE($ue,$etab, $conn) {
    // Préparation de la requête SQL
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM ecue WHERE code_ue = ? and etab=?");
    $stmt->bind_param("ss", $ue,$etab); // "s" indique que le paramètre est une chaîne de caractères

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $result = $stmt->get_result();
    
    // Récupération du nombre de lignes retournées
    $row = $result->fetch_assoc();
    $count = $row["count"];


    // Retourne le nombre de lignes correspondant à l'UE dans la table "ecue"
    return $count;
}


function getClasseEnseignant($enseignant, $annee, $etab, $connexion) {
    // Prepare the SQL query to prevent SQL injection
    $sql = "SELECT DISTINCT(classe) FROM repartition_enseignant WHERE code = ? AND etab = ? AND annee = ?";

    // Prepare the statement
    $stmt = $connexion->prepare($sql);
    if (!$stmt) {
        die("Erreur lors de la préparation de la requête : " . $connexion->error);
    }

    // Bind parameters
    $stmt->bind_param("sss", $enseignant, $etab, $annee);

    // Execute the query
    if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();

        // Fetch all rows into an array
        $classes = [];
        while ($row = $result->fetch_assoc()) {
            $classes[] = $row['classe'];
        }

        // Free result and return the array
        $result->free();
        return $classes;
    } else {
        // Handle query execution error
        die("Erreur lors de l'exécution de la requête : " . $stmt->error);
    }
}


function getClasseInRepartition($ue,$etab,$connexion){

    $sql="SELECT classe from repartition where ue='$ue' AND etab='$etab'";

    $resultat = $connexion->query($sql);
    if ($resultat) {
        $row = $resultat->fetch_assoc();
        $count = $row['classe'];

    

        return $count ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}
function getEtablissementUtilisateur($id,$connexion){

    $sql ="select etab from utilisateur where id=$id";

    $resultat = $connexion->query($sql);
    if ($resultat) {
        $row = $resultat->fetch_assoc();
        $count = $row['etab'];

    

        return $count ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}

function getEtabByLibelleParcours($conn, $libelleparcours) {
    // Échapper les données pour éviter les attaques par injection SQL

 
    $libelleparcours = mysqli_real_escape_string($conn, $libelleparcours);

    // Préparer la requête SQL paramétrée
    $query = "SELECT etab FROM parcours WHERE libelle = ?";

    // Préparer la déclaration
    $stmt = $conn->prepare($query);

    // Vérifier si la préparation a échoué
    if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    // Lier le paramètre à la déclaration
    $stmt->bind_param("s", $libelleparcours);

    // Exécuter la requête
    $stmt->execute();

    // Lier le résultat de la requête à une variable

    $etab=null;
    $stmt->bind_result($etab);

    // Récupérer la valeur
    $stmt->fetch();

    // Fermer la déclaration
    $stmt->close();

    // Retourner la valeur
    return $etab;
}


function enregistrerDansJDP($parcours, $numero, $role, $titre, $enseignant, $etablissement, $connexion) {
    // Préparation de la requête SQL d'insertion
    $requete = "INSERT INTO jdp (parcours, deliberation, role, titre, membre, etab) VALUES (?, ?, ?, ?, ?, ?)";
    
    // Préparation de la déclaration
    $statement = $connexion->prepare($requete);
    
    // Liaison des valeurs aux paramètres de la déclaration
    $statement->bind_param("ssssss", $parcours, $numero, $role, $titre, $enseignant, $etablissement);
    
    // Exécution de la requête
    $resultat = $statement->execute();
    
    if ($resultat) {
      //
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}



function genererCodeAnonymat() {

    $number = mt_rand(0, 9);
    
   
    $letter = chr(mt_rand(65, 90)); 
    $code = $letter . $number;

    return $code;
}

function genererCodeDeliberation() {

    $number = mt_rand(0, 9);
    
   
    $letter = chr(mt_rand(70, 90)); 
    $code =   $number.$letter;
    return $code;
}
function replace($nom)
{

    return str_replace("+","'",$nom);
}

function getSpecialiteByUE($ue, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT specialite FROM ue WHERE code = ?");
    $specialite =null;
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("s", $ue);
    
    // Exécution de la requête
    $requete->execute();
    
    // Liaison du résultat de la requête à une variable
    $requete->bind_result($specialite);
    
    // Récupération du résultat
    $requete->fetch();
    
    return $specialite;
}
function getSpecialiteByAnonymat($code, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT specialite FROM anonymat WHERE numero = ?");
    $specialite =null;
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("s", $code);
    
    // Exécution de la requête
    $requete->execute();
    
    // Liaison du résultat de la requête à une variable
    $requete->bind_result($specialite);
    
    // Récupération du résultat
    $requete->fetch();
    
    return $specialite;
}

function getSemestreByAnonymat($code, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT semestre FROM anonymat WHERE numero = ?");
    $semestre =null;
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("s", $code);
    
    // Exécution de la requête
    $requete->execute();
    
    // Liaison du résultat de la requête à une variable
    $requete->bind_result($semestre);
    
    // Récupération du résultat
    $requete->fetch();
    
    return $semestre;
}

function getClasseByAnonymat($code, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT classe FROM anonymat WHERE numero = ?");
    $classe =null;
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("s", $code);
    
    // Exécution de la requête
    $requete->execute();
    
    // Liaison du résultat de la requête à une variable
    $requete->bind_result($classe);
    
    // Récupération du résultat
    $requete->fetch();
    
 
    
    // Retourne la spécialité associée à la classe
    return $classe;
}

function getClasseByInscription($code, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT classe FROM inscription WHERE id = ?");
    $classe =null;
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("i", $code);
    
    // Exécution de la requête
    $requete->execute();
    
    // Liaison du résultat de la requête à une variable
    $requete->bind_result($classe);
    
    // Récupération du résultat
    $requete->fetch();
    
 
    
    // Retourne la spécialité associée à la classe
    return $classe;
}
function getSpecialiteByInscription($code, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT specialite FROM inscription join candidat on candidat.code = inscription.candidat  WHERE inscription.id = ?");
    $classe =null;
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("i", $code);
    
    // Exécution de la requête
    $requete->execute();
    
    // Liaison du résultat de la requête à une variable
    $requete->bind_result($classe);
    
    // Récupération du résultat
    $requete->fetch();
    
 
    
    // Retourne la spécialité associée à la classe
    return $classe;
}

function getParcours($spec, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT parcours from specialite where libelle=?");
    $classe =null;
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("s", $spec); 
    // Exécution de la requête
    $requete->execute();
    
    // Liaison du résultat de la requête à une variable
    $requete->bind_result($classe);
    
    // Récupération du résultat
    $requete->fetch();
    
 
    
    // Retourne la spécialité associée à la classe
    return $classe;
}



function getNoteDevoir($conn, $etudiant, $semestre, $annee, $ecue) {
    // Préparation de la requête SQL pour récupérer les notes de l'étudiant pour cette ECUE
    $sql = "SELECT moyDev FROM notation 
            WHERE inscription = ? AND semestre = ? AND annee = ? AND code_ecue = ?";
    $stmt = $conn->prepare($sql);

    // Liaison des paramètres
    $stmt->bind_param("isss", $etudiant, $semestre, $annee, $ecue);

    // Exécution de la requête
    $stmt->execute();

    // Liaison des résultats
    $moyDev = 0; 
    $stmt->bind_result($moyDev);

    // Création d'un tableau pour stocker les notes
    $notes =null;

    // Récupération des résultats
    while ($stmt->fetch()) {
        // Stockage des notes dans le tableau associatif
        $notes = $moyDev;
        
    }

    // Fermeture du statement
   
    // Retourne les notes sous forme de tableau associatif
    return $notes;
}

function getNoteExamen($conn, $etudiant, $semestre, $annee, $ecue) {
    // Préparation de la requête SQL pour récupérer les notes de l'étudiant pour cette ECUE
    $sql = "SELECT moyEx FROM notation 
            WHERE inscription = ? AND semestre = ? AND annee = ? AND code_ecue = ?";
    $stmt = $conn->prepare($sql);

    // Liaison des paramètres
    $stmt->bind_param("isss", $etudiant, $semestre, $annee, $ecue);

    // Exécution de la requête
    $stmt->execute();

    // Liaison des résultats
    $moyEx=0;
    $stmt->bind_result($moyEx);

    // Création d'un tableau pour stocker les notes
    $notes =null;

    // Récupération des résultats
    while ($stmt->fetch()) {
        // Stockage des notes dans le tableau associatif
        $notes = $moyEx;
        
    }

    // Fermeture du statement
   
    // Retourne les notes sous forme de tableau associatif
    return $notes;
}

function getNoteRattrapage($conn, $etudiant, $semestre, $annee, $ecue) {
    // Préparation de la requête SQL pour récupérer les notes de l'étudiant pour cette ECUE
    $sql = "SELECT session_rappel FROM notation 
            WHERE inscription = ? AND semestre = ? AND annee = ? AND ecue = ?";
    $stmt = $conn->prepare($sql);

    // Liaison des paramètres
    $stmt->bind_param("isss", $etudiant, $semestre, $annee, $ecue);

    // Exécution de la requête
    $stmt->execute();

    // Liaison des résultats
    $moyEx=0;
    $stmt->bind_result($moyEx);

    // Création d'un tableau pour stocker les notes
    $notes =null;

    // Récupération des résultats
    while ($stmt->fetch()) {
        // Stockage des notes dans le tableau associatif
        $notes = $moyEx;
        
    }

    // Fermeture du statement
   
    // Retourne les notes sous forme de tableau associatif
    return $notes;
}

function getMoyenneUE($conn, $etudiant, $semestre, $annee, $ue,$etab) {
   
   $sql = "SELECT moyenneUE FROM vue_moyenne_ue  
        WHERE inscription = ? AND semestre = ? AND annee = ? AND ue = ? AND etab = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erreur lors de la préparation de la requête : " . $conn->error);
}

// Liaison des paramètres
$stmt->bind_param("issss", $etudiant, $semestre, $annee, $ue, $etab);

// Exécution de la requête
if (!$stmt->execute()) {
    die("Erreur lors de l'exécution de la requête : " . $stmt->error);
}

// Liaison du résultat
$moyEx = "-";
$stmt->bind_result($moyEx);

// Récupération des résultats
$notes = "-"; // Valeur par défaut si aucun résultat n'est trouvé
if ($stmt->fetch()) {
    $notes = $moyEx; // Stocker la moyenne si un résultat est trouvé
}



// Retourne le résultat
return $notes;


}

function getStatutValidationUE($conn, $etudiant, $semestre, $annee, $ue,$etab) {
    // Préparation de la requête SQL pour récupérer les notes de l'étudiant pour cette ECUE
    $sql = "SELECT statut FROM vue_moyenne_ue  
            WHERE inscription = ? AND semestre = ? AND annee = ? AND ue = ? and etab=?";
    $stmt = $conn->prepare($sql);

    // Liaison des paramètres
    $stmt->bind_param("issss", $etudiant, $semestre, $annee, $ue,$etab);

    // Exécution de la requête
    $stmt->execute();

    // Liaison des résultats
    $moyEx=0;
    $stmt->bind_result($moyEx);

    // Création d'un tableau pour stocker les notes
    $notes =null;

    // Récupération des résultats
    while ($stmt->fetch()) {
        // Stockage des notes dans le tableau associatif
        $notes = $moyEx;
        
    }
    return $notes;
}




// Fonction pour récupérer la spécialité associée à une classe
function recupererSpecialiteParClasse($nomClasse, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT specialite FROM classe WHERE libelle = ?");
    $specialite =null;
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("s", $nomClasse);
    
    // Exécution de la requête
    $requete->execute();
    
    // Liaison du résultat de la requête à une variable
    $requete->bind_result($specialite);
    
    // Récupération du résultat
    $requete->fetch();
    
 
    
    // Retourne la spécialité associée à la classe
    return $specialite;
}
function verifierEcueClasse($ecue, $nomClasse, $etab, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT COUNT(*) AS count FROM vue_repartition WHERE code_ecue = ? AND classe = ? and etab = ? ");
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("sss", $ecue, $nomClasse, $etab);
    
    // Exécution de la requête
    $requete->execute();
    
    // Récupération du résultat
    $resultat = $requete->get_result();
    
    // Récupération de la première ligne du résultat
    $ligne = $resultat->fetch_assoc();
    
    // Récupération du nombre de résultats trouvés
    $nombreResultats = $ligne['count'];
    
  
    return $nombreResultats > 0;
}
function verifierEcueClasseSemestre($ecue, $nomClasse,$semestre, $etab, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT COUNT(*) AS count FROM vue_repartition where vue_repartition.code_ecue = ? AND vue_repartition.classe = ? and vue_repartition.etab = ? and vue_repartition.semestre = ? ");
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("ssss", $ecue, $nomClasse, $etab,$semestre);
    
    // Exécution de la requête
    $requete->execute();
    
    // Récupération du résultat
    $resultat = $requete->get_result();
    
    // Récupération de la première ligne du résultat
    $ligne = $resultat->fetch_assoc();
    
    // Récupération du nombre de résultats trouvés
    $nombreResultats = $ligne['count'];
    
  
    return $nombreResultats > 0;
}
function verifierCodeAnonyme($code,$ecue, $annee,$examen,$connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT COUNT(*) AS count FROM anonymat WHERE numero = ? AND code_ecue=? and  annee = ? and type=?");
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("ssss", $code,$ecue, $annee,$examen);
    
    // Exécution de la requête
    $requete->execute();
    
    // Récupération du résultat
    $resultat = $requete->get_result();
    
    // Récupération de la première ligne du résultat
    $ligne = $resultat->fetch_assoc();
    
    // Récupération du nombre de résultats trouvés
    $nombreResultats = $ligne['count'];
    

    return $nombreResultats > 0;
}
// Fonction pour vérifier si un élève est inscrit dans une classe pour une année donnée
function estInscritDansClasse($numeroEleve, $nomClasse, $annee, $connexion) {
    // Préparation de la requête SQL avec un paramètre
    $requete = $connexion->prepare("SELECT COUNT(*) AS count FROM inscription WHERE id = ? AND classe = ? AND annee = ?");
    
    // Liaison des valeurs aux paramètres de la requête
    $requete->bind_param("iss", $numeroEleve, $nomClasse, $annee);
    
    // Exécution de la requête
    $requete->execute();
    
    // Récupération du résultat
    $resultat = $requete->get_result();
    
    // Récupération de la première ligne du résultat
    $ligne = $resultat->fetch_assoc();
    
    // Récupération du nombre de résultats trouvés
    $nombreResultats = $ligne['count'];
    
    // Fermeture de la requête
    $requete->close();
    
    // Retourne vrai si l'élève est inscrit, faux sinon
    return $nombreResultats > 0;
}
function  getNomByInscription($inscription,$connexion){

    $sql ="select nom,prenom from candidat where code in ( select candidat from inscription where id=$inscription)";
    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return  strtoupper( $row['nom']." ".$row['prenom']);
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}



// Fonction pour obtenir la liste des ECUE associés à une UE
function getListeEcueFromUE($ue, $etab, $conn) {
    // Initialisation de la liste des ECUE
    $liste_ecue = array();
    
    // Préparation de la requête SQL
    $stmt = $conn->prepare("SELECT libelle FROM ecue WHERE ue = ? and etab=?");
    $stmt->bind_param("ss", $ue,$etab); // "s" indique que le paramètre est une chaîne de caractères

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $result = $stmt->get_result();
    
    // Parcourir les résultats et ajouter les libellés des ECUE à la liste
    while ($row = $result->fetch_assoc()) {
        $liste_ecue[] = $row["libelle"];
    }

   
    // Retourner la liste des ECUE
    return $liste_ecue;
}



function verifierExistenceUEDansRepartition($ue,$etab, $conn) {
    // Préparation de la requête SQL
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM vue_repartition WHERE ue = ? and etab=?");
    $stmt->bind_param("ss", $ue,$etab); // "s" indique que le paramètre est une chaîne de caractères

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $result = $stmt->get_result();
    
    // Récupération du nombre de lignes retournées
    $row = $result->fetch_assoc();
    $count = $row["count"];

    // Fermeture du statement
    $stmt->close();
    
    // Si le nombre de lignes retournées est supérieur à zéro, l'UE existe dans la table
    return $count > 0;
}



function enregistrerRepartition($classe, $ue, $etablissement, $conn) {
    // Récupérer la liste des ECUE associés à l'UE
    $liste_ecue = getListeEcueFromUE($ue,$etablissement, $conn);
    
    // Pour chaque ECUE, enregistrer une entrée dans la table repartition
    foreach ($liste_ecue as $ecue) {
        // Préparation de la requête SQL
        $stmt = $conn->prepare("INSERT INTO repartition (classe, ecue, ue, etablissement) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $classe, $ecue, $ue, $etablissement); // "s" indique que les paramètres sont des chaînes de caractères

        // Exécution de la requête
        $stmt->execute();
    }
}



function getUEFromEcue($libelle, $conn) {
    // Préparation de la requête SQL
    $stmt = $conn->prepare("SELECT ue FROM ecue WHERE code_ecue = ?");
    $stmt->bind_param("s", $libelle); // "s" indique que le paramètre est une chaîne de caractères

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $result = $stmt->get_result();
    
    // Si au moins une ligne est retournée
    if ($result->num_rows > 0) {
        // Récupération de la première ligne
        $row = $result->fetch_assoc();
        // Retourne la valeur de la colonne "ue"
        return $row["ue"];
    } else {
        // Si aucun résultat n'est retourné, retourne NULL
        return null;
    }

}


function statutSoutenance($moy){
    if($moy == null){
        return null;
    }
     else  if($moy <10){
        return "Ajourné";
       }else if($moy >= 10){
         return "Admis";
       }
}

function mentionParmoyenne($moyenne){

 if($moyenne == null){
    return null;
 }
   else if($moyenne >= 0 and $moyenne <= 5){

        return "Médiocre";
    }else if($moyenne > 5 and $moyenne <=9){
        return "Insuffisant";
    }else if($moyenne > 9 and  $moyenne <=11){
        return "Passable";
    }else if($moyenne > 11 and $moyenne <= 14){
        return "Assez-Bien";
    }else if($moyenne >= 14 and $moyenne <=15){
        return "Bien";
    }else if($moyenne > 15 and $moyenne <=17){
        return "Très-Bien";
    }else {
        return "Excellent";
    }
}
function VerifierPayementFraisSoutenance($matricule, $annee, $connexion) {
    // Échapper les valeurs pour éviter les injections SQL
    $matricule = mysqli_real_escape_string($connexion, $matricule);
    $annee = mysqli_real_escape_string($connexion, $annee);
    // Construire la requête SQL
    $sql ="select COUNT(*) as nombre_etudiants from inscription where candidat='$matricule' and annee='$annee' and id in ( select inscription from frais where libelle='frais de soutenance')";

    $resultat = mysqli_query($connexion, $sql);

    if ($resultat) {
       
        $row = mysqli_fetch_assoc($resultat);
        $nombre_etudiants = $row['nombre_etudiants'];

        // Si le nombre est supérieur à 0, l'étudiant est a payé les frais de soutenance
        return $nombre_etudiants > 0;
    } else {
        // En cas d'erreur lors de l'exécution de la requête
        echo "Erreur lors de la requête : " . mysqli_error($connexion);
        return false;
    }
}

function VerifierPayementReinscription($matricule, $annee, $connexion) {
    // Échapper les valeurs pour éviter les injections SQL
    $matricule = mysqli_real_escape_string($connexion, $matricule);
    $annee = mysqli_real_escape_string($connexion, $annee);
    // Construire la requête SQL
    $sql ="select COUNT(*) as nombre_etudiants from inscription where candidat='$matricule'  and id in ( select inscription from frais where libelle='inscription' and annee_inscription='$annee')";

    $resultat = mysqli_query($connexion, $sql);

    if ($resultat) {
       
        $row = mysqli_fetch_assoc($resultat);
        $nombre_etudiants = $row['nombre_etudiants'];

        // Si le nombre est supérieur à 0, l'étudiant est a payé les frais de soutenance
        return $nombre_etudiants > 0;
    } else {
        // En cas d'erreur lors de l'exécution de la requête
        echo "Erreur lors de la requête : " . mysqli_error($connexion);
        return false;
    }
}
function getNoteElement($soutenance,$element,$etab,$connexion)
{
    $sql ="select note from eval where element='$element' and soutenance='$soutenance' and etab='$etab'";
       // Exécuter la requête
       $resultat = mysqli_query($connexion, $sql);

      $resultat = $connexion->query($sql);

      if ($resultat->num_rows > 0) {
          // Récupération du nombre total de cours
          $row = $resultat->fetch_assoc();
          $note = $row["note"];
  
  
          return $note ;
      } else {
        
  
          return null;
      }
   
}
function verifierImpetrant($matricule,$connexion,$annee)
{
       $sql ="select COUNT(*) as nombre_etudiants from inscription where candidat='$matricule' and annee='$annee' ";
       // Exécuter la requête
    $resultat = mysqli_query($connexion, $sql);

    if ($resultat) {
        // Récupérer le nombre d'étudiants correspondant au matricule et à l'année
        $row = mysqli_fetch_assoc($resultat);
        $nombre_etudiants = $row['nombre_etudiants'];

        // Si le nombre est supérieur à 0, l'étudiant est inscrit
        return $nombre_etudiants > 0;
    } else {
        
        echo "Erreur lors de la requête : " . mysqli_error($connexion);
        return false;
    }

  }



// Fonction pour vérifier si l'année du bac est valide par rapport à la date de naissance
function validateYearBac($yearBac, $dateNaissance) {
    $annee = date("Y", strtotime($dateNaissance));
    // Vérifier si l'année du bac est supérieure à l'année de naissance
    return ($yearBac > $annee);
}
function generateCodeSoutenance() {
    $prefixe = 'SUDSN-'; 
    $code = $prefixe . uniqid(); // Ajouter un identifiant unique basé sur la date et l'heure actuelles
    return $code;
}

function generateCodeContrat() {
    $prefixe = 'DAARSPE-'; 
    $code = $prefixe . uniqid(); // Ajouter un identifiant unique basé sur la date et l'heure actuelles
    return $code;
}

function generateReference() {
    $prefixe = 'REF/UDSN'.date("Y"); 
    $code = $prefixe . uniqid(); 
    return $code;
}
function generateCodeFrais() {
    $prefixe = 'UDSN-'; 
    $code = $prefixe . uniqid();
    return $code;
}
function generateUniqueCode() {
   
    $counterFile = "counter.txt";

    
    if (!file_exists($counterFile)) {
      
        file_put_contents($counterFile, 1);
    }

    $counter = (int)file_get_contents($counterFile);

   
    $year = date("y");

    $code = $year . str_pad($counter, 5, "0", STR_PAD_LEFT); 
    file_put_contents($counterFile, $counter + 1);

    return $code;
}
function generateUniqueDemande() {
    $prefixe = 'DEM/'; // Vous pouvez personnaliser le préfixe
    $code = $prefixe . uniqid(); // Ajouter un identifiant unique basé sur la date et l'heure actuelles
    return $code;
}
function clean_input($data) {
    
   
    $da=str_replace("'","+",$data);
    return $da;
}

// Fonction pour vérifier si une spécialité appartient à un établissement
function isSpecialiteInEtablissement($conn,$specialite, $etablissement) {
   

    // Échapper les données pour prévenir les injections SQL
    $specialite = clean_input($specialite);
    $etablissement =clean_input($etablissement);

    // Requête SQL pour vérifier si la spécialité appartient à l'établissement
    $sql = "SELECT COUNT(*) as count FROM specialite WHERE libelle = '$specialite' AND etab = '$etablissement'";
    
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'];

    

        return $count > 0;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
}

function getcodeUnique($d,$connexion) {


    $sql = "SELECT code FROM enseignant WHERE contrat='$d'";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return $row['code'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}
function getCodeCandidat($idCandidat,$connexion) {


    $sql = "SELECT code FROM candidat WHERE utilisateur=$idCandidat";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return $row['code'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}
function getstatut($id,$connexion) {


    $sql = "SELECT statut FROM utilisateur WHERE id =$id";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return $row['statut'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}
function getlogo($id,$connexion) {


    $sql = "SELECT logo FROM univ WHERE code in ( select univ from utilisateur where id =$id)";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return $row['logo'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}
function getimguser($id,$connexion) {


    $sql = "SELECT img FROM utilisateur  where id =$id";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return $row['img'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}


function getStatutCandidat($idCandidat,$connexion) {


    $sql = "SELECT statut FROM candidat WHERE utilisateur =$idCandidat";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return $row['statut'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}
function getAnneeCandidat($idCandidat,$connexion) {


    $sql = "SELECT annee FROM candidat WHERE utilisateur =$idCandidat";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return $row['annee'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}
function getImgCandidat($idCandidat,$connexion) {


    $sql = "SELECT img FROM candidat WHERE utilisateur =$idCandidat";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

        return $row['img'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}

function genererNomFichier($longueur = 10) {
    // Caractères possibles pour le nom du fichier
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Calcul de la longueur de la chaîne de caractères
    $longueurCaracteres = strlen($caracteres);

    // Initialisation de la chaîne de caractères résultante
    $nomFichier = '';

    // Génération aléatoire du nom du fichier
    for ($i = 0; $i < $longueur; $i++) {
        $indexAleatoire = rand(0, $longueurCaracteres - 1);
        $nomFichier .= $caracteres[$indexAleatoire];
    }

    return $nomFichier;
}

function idCandidatExiste1($idCandidat,$conn) {
 

    // Requête SQL pour vérifier si l'ID existe dans la table candidat
    $sql = "SELECT COUNT(*) as count FROM candidat WHERE utilisateur = '$idCandidat'";

    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['count'] > 0;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
}

function idCandidatExiste2($idCandidat,$conn) {
 

    // Requête SQL pour vérifier si l'ID existe dans la table candidat
    $sql = "SELECT COUNT(*) as count FROM candidat WHERE code = '$idCandidat'";

    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['count'] > 0;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
}

function idCandidatValide($code,$conn,$annee) {
 

    // Requête SQL pour vérifier si l'ID existe dans la table candidat
    $sql = "SELECT COUNT(*) as count FROM candidat WHERE code = '$code' and annee='$annee' ";

    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['count'] > 0;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
}



function getSemestreEcue($code,$conn) {
 

    // Requête SQL pour vérifier si l'ID existe dans la table candidat
    $sql = "SELECT semestre  FROM ue where libelle in ( select ue from ecue where libelle = '$code')";

    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['semestre'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
}

function verifierNoteGroupe($type,$note,$conn){
    $somme =0;$note_max =0;
    $sql="select sum(note_max) as notes from element where type_element='".$type."'";
    $result = $conn->query($sql);
    if ($result->num_rows >0) {
        $row = $result->fetch_assoc();
        $somme= $row['notes'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
    $sql ="select note_max from type_element where libelle_type='".$type."'";
    $result = $conn->query($sql);
    if ($result->num_rows >0) {
        $row = $result->fetch_assoc();
        $note_max= $row['note_max'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }


    $somme = $somme + $note;

    if($somme > $note_max){
        return false;
    }else{ 
      return  true;
    }


}
function getStatutPaimentCand($code,$annee,$conn,) {
 

    // Requête SQL pour vérifier si l'ID existe dans la table candidat
    $sql = "SELECT statut_paiement FROM candidat WHERE code = '$code' and annee='$annee'";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        $row = $result->fetch_assoc();

       
        return $row['statut_paiement'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
}

function getLibelleEtablissement($code,$conn,) {
 

    // Requête SQL pour vérifier si l'ID existe dans la table candidat
    $sql = "SELECT libelle as lib FROM etablissement WHERE code = '$code'";

    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['lib'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
}

// Fonction pour vérifier si un ID existe dans la table candidat
function idCandidatExiste($idCandidat,$conn,$annee) {
 

    // Requête SQL pour vérifier si l'ID existe dans la table candidat
    $sql = "SELECT COUNT(*) as count FROM candidat WHERE utilisateur = '$idCandidat' and annee='$annee'";

    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['count'] > 0;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $conn->error);
    }
}

// Fonction de vérification du candidat
function checkCandidateValidation($conn, $candidateCode, $year) {
    // Préparer la requête SQL avec une requête préparée pour éviter les attaques par injection SQL
    $sql = "SELECT * FROM validation WHERE candidat = ? AND annee = ?";
    $stmt = $conn->prepare($sql);

    // Lier les paramètres
    $stmt->bind_param("ss", $candidateCode, $year);

    // Exécuter la requête
    $stmt->execute();

    // Récupérer le résultat
    $result = $stmt->get_result();

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        return true; // Le candidat est valide
    } else {
        return false; // Le candidat n'est pas valide
    }
}

function enregistrerNote($etudiant, $classe,$ecue, $moyenneDevoir, $moyenneExamen, $semestre, $annee, $etab,$connexion) {
    // Requête SQL pour insérer une ligne de notation

    $ex=($moyenneDevoir+$moyenneExamen)/2;                                                                                                         
    $sql= "INSERT INTO notation (inscription,classe,ecue,annee,moyDev,moyEx,moyGen,etab,semestre)  VALUES ($etudiant,'$classe','$ecue','$annee',$moyenneDevoir,$moyenneExamen,$ex,'$etab','$semestre')";
    

  if($connexion->query($sql)){
    return true;
  }else{
    return $connexion->error;
  }
    
}

function obtenirNomPrenom($codeCandidat, $anneeScolaire, $connexion) {
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT prenom, nom FROM candidat WHERE code = ? AND annee = ?");
        $requete->bind_param('ss', $codeCandidat, $anneeScolaire);
        $requete->execute();


        $prenom = null; $nom=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($prenom, $nom);

        // Récupérez les résultats de la requête
        $requete->fetch();

        // Fermez la requête
        $requete->close();

        if ($prenom !== null && $nom !== null) {
            return  $nom."  ".$prenom;
        } else {
            return "Candidat non trouvé.";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}
function getThByEns($enseignant,$connexion,$etab){
    $sql =" select th from enseignant where code='$enseignant' and etab='$etab'";
     $result = $connexion->query($sql);
 if ($result) {
    $row = $result->fetch_assoc();
    return $row['th'] ;
} else {
    // Gestion de l'erreur
    die("Erreur lors de la requête SQL : " . $connexion->error);
}
}
function obtenirNomPrenomFromInscription($inscription, $annee,$connexion) {
    $sql = "SELECT candidat as lib FROM inscription WHERE id = $inscription and annee='$annee'";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['lib'] ;
    } else {
        // Gestion de l'erreur


             
                  $sql ="select candidat from inscription where id=$inscription";

                  $result = $connexion->query($sql);

                       if ($result) {
                         $row = $result->fetch_assoc();

       
                       return $row['candidat'] ;
                    } else{

                        die("Erreur lors de la requête SQL : " . $connexion->error);

                    }


       
    }
       
}
function getSpecialiteClasse($connexion,$classe){
     
    $sql ="select specialite from classe where libelle='$classe'";

    $result = $connexion->query($sql);

         if ($result) {
           $row = $result->fetch_assoc();

         return $row['specialite'] ;
      } else{

          die("Erreur lors de la requête SQL : " . $connexion->error);

      }


}


function getPrenomEtudiant($id,$connexion,$etablissement){
    $sql ="select prenom from candidat where code='$id' and etab='$etablissement'";

    $result = $connexion->query($sql);

         if ($result->num_rows > 0) {
           $row = $result->fetch_assoc();

         return strtolower($row['prenom']);
      } else{

          die("Erreur lors de la requête SQL : " . $connexion->error);

      }
}


function getEtudiantCC($etudiant,$connexion,$etablissement,$semestre,$ecue,$annee){

    $sql ="select moyDev from notation where inscription='$etudiant' and etab='$etablissement' and semestre='$semestre' and ecue='$ecue' and annee='$annee'";
    $result = $connexion->query($sql);

         if ($result->num_rows > 0) {
           $row = $result->fetch_assoc();
                 

           if($row['moyDev'] !== null){
            return round($row['moyDev'],2) ;
           }else{
            return "-";
           }
        
      } else{

         return "-";

      }
}

function getEtudiantEXT($etudiant,$connexion,$etablissement,$semestre,$ecue,$annee){

    $sql ="select moyEx from notation where inscription='$etudiant' and etab='$etablissement' and semestre='$semestre' and ecue='$ecue' and annee='$annee'";
    $result = $connexion->query($sql);

         if ($result->num_rows > 0) {
           $row = $result->fetch_assoc();

           if($row['moyEx'] !== null){
            return round($row['moyEx'],2) ;
           }else{
            return "-";
           }

        
      } else{

          return "-";

      }
}

function countUE($specialite,$etablissement,$niveau,$connexion){


    $sql ="select COUNT(libelle_ue) as ma from vue_repartition where classe in ( select libelle from classe where specialite='$specialite' and niveau='$niveau') and etab='$etablissement'";
     $result=  $connexion->query($sql);

     if ($result) {
        $row = $result->fetch_assoc();

      return $row['ma'] ;
   } else{

       die("Erreur lors de la requête SQL : " . $connexion->error);

   }

}

function getEtudiantRattrapage($etudiant,$connexion,$etablissement,$semestre,$ecue,$annee){

    $sql ="select session_rappel from notation where inscription='$etudiant' and etab='$etablissement' and semestre='$semestre' and ecue='$ecue' and annee='$annee'";
    $result = $connexion->query($sql);

         if ($result->num_rows > 0) {
           $row = $result->fetch_assoc();


           if($row['session_rappel'] !== null){
            return round($row['session_rappel'],2) ;
           }else{
            return "-";
           }

         
      } else{

        return "-";

      }
}

function getParcoursUser($id_user,$connexion){
    $sql ="select parcours from utilisateur where id=$id_user";

    $r=$connexion->query($sql);

    if($r->num_rows > 0){

        while($t = $r->fetch_object()){
            $p = $t->parcours;
        }

        return $p;

    }else{
        return null;
    }
}
function getSemestreUser($id_user,$connexion){
    $sql ="select semestre from utilisateur where id=$id_user";

    $r=$connexion->query($sql);

    if($r->num_rows > 0){

        while($t = $r->fetch_object()){
            $p = $t->semestre;
        }

        return $p;

    }else{
        return null;
    }
}
function getExamenUser($id_user,$connexion){
    $sql ="select examen from utilisateur where id=$id_user";

    $r=$connexion->query($sql);

    if($r->num_rows > 0){

        while($t = $r->fetch_object()){
            $p = $t->examen;
        }

        return $p;

    }else{
        return null;
    }
}

function getAnneeUser($id_user,$connexion){
    $sql ="select annee from utilisateur where id=$id_user";

    $r=$connexion->query($sql);

    if($r->num_rows > 0){

        while($t = $r->fetch_object()){
            $p = $t->annee;
        }

        return $p;

    }else{
        return null;
    }
}

// Fonction pour vérifier si une entrée existe dans la table "recap"
function verifierEntree($connexion, $etudiant, $semestre, $examen, $annee, $etablissement) {
    // Préparer la requête SQL
    $requete = "SELECT COUNT(*) AS nb_entrees FROM recap WHERE etudiant = ? AND semestre = ? AND examen = ? AND annee = ? AND etab = ?";
    
    // Préparer la déclaration de la requête
    $stmt = $connexion->prepare($requete);

    // Exécuter la requête en liant les paramètres
    $stmt->bind_param("issss", $etudiant, $semestre, $examen, $annee, $etablissement);
    $stmt->execute();
    
    // Récupérer le résultat de la requête
    $resultat = $stmt->get_result();
    
    // Lire la ligne de résultat
    $row = $resultat->fetch_assoc();
    
    // Retourner vrai si une entrée existe, sinon faux
    return $row['nb_entrees'] > 0;
}

// Fonction pour obtenir la moyenne d'une entrée dans la table "recap"
function obtenirMoyenne($connexion, $etudiant, $semestre, $examen, $annee, $etablissement) {
    // Préparer la requête SQL
    $requete = "SELECT moy FROM recap WHERE etudiant = ? AND semestre = ? AND examen = ? AND annee = ? AND etab = ?";
    
    // Préparer la déclaration de la requête
    $stmt = $connexion->prepare($requete);

    // Exécuter la requête en liant les paramètres
    $stmt->bind_param("issss", $etudiant, $semestre, $examen, $annee, $etablissement);
    $stmt->execute();
    
    // Récupérer le résultat de la requête
    $resultat = $stmt->get_result();
    
    // Lire la ligne de résultat
    $row = $resultat->fetch_assoc();
    
    // Retourner la moyenne
    return $row['moy'];
}

// Fonction pour obtenir la décision d'une entrée dans la table "recap"
function obtenirDecision($connexion, $etudiant, $semestre, $examen, $annee, $etablissement) {
    // Préparer la requête SQL
    $requete = "SELECT decision FROM recap WHERE etudiant = ? AND semestre = ? AND examen = ? AND annee = ? AND etab = ?";
    
    // Préparer la déclaration de la requête
    $stmt = $connexion->prepare($requete);

    // Exécuter la requête en liant les paramètres
    $stmt->bind_param("issss", $etudiant, $semestre, $examen, $annee, $etablissement);
    $stmt->execute();
    
    // Récupérer le résultat de la requête
    $resultat = $stmt->get_result();
    
    // Lire la ligne de résultat
    $row = $resultat->fetch_assoc();
    
    // Retourner la décision
    return $row['decision'];
}



function getNomEtudiant($id,$connexion,$etablissement){
    $sql ="select nom from candidat where code='$id' and etab='$etablissement' ";

    $result = $connexion->query($sql);

         if ($result->num_rows > 0) {
           $row = $result->fetch_assoc();

         return strtoupper($row['nom']) ;
      } else{

          die("Erreur lors de la requête SQL : " . $connexion->error);

      }
}

function getAnneeInscription($connexion,$id,$etab){
    $sql ="select annee from inscription where id=$id and etab='$etab'";

    $result = $connexion->query($sql);

         if ($result->num_rows > 0) {
           $row = $result->fetch_assoc();

         return $row['annee'] ;
      } else{

    
            return ("Erreur lors de la requête SQL : " . $connexion->error);
      }
}
function verifierFraisReinscription($conn, $etudiant, $annee) {
    // Préparation de la requête SQL pour vérifier si l'étudiant a payé les frais d'inscription pour cette année
    $count = null;
    $sql = "SELECT COUNT(*) AS count FROM frais WHERE inscription = ? AND libelle='inscription' AND annee_inscription = ?";
    $stmt = $conn->prepare($sql);
  // Liaison des paramètres
    $stmt->bind_param("is", $etudiant, $annee);

    // Exécution de la requête
    $stmt->execute();

    // Liaison des résultats
    $stmt->bind_result($count);

    // Récupération du résultat

    // Fermeture du statement
    $stmt->close();

    // Si le compte est supérieur à zéro, cela signifie que l'étudiant a payé les frais d'inscription pour cette année
    return $count > 0;
}

function verifierIdentityEtudiant($connexion, $matricule, $nom,$prenom,$etab) {
    
    $sql = "SELECT COUNT(*) AS count FROM candidat WHERE code ='$matricule' AND nom='$nom' AND prenom ='$prenom' AND  etab='$etab'";
  
    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
          

       
        return $row['count'] > 0 ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
}

function getNomImpetrant($imp,$connexion) {
    $sql = "SELECT candidat  FROM inscription WHERE id = $imp";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['candidat'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
       
}
function getDateNaissanceCandidat($codeCandidat,$etablissement,$connexion){

    $sql = "SELECT date_nais  FROM candidat WHERE code ='$codeCandidat' and etab='$etablissement' ";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['date_nais'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
       
}


function getSpecialitetudiant($inscription,$etablissement,$connexion){

    $sql = "SELECT classe.specialite as spec from classe join inscription on inscription.classe=classe.libelle  where inscription.id=$inscription and inscription.etab='$etablissement'";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['spec'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
       
}
function getNiveauEtudiant($inscription,$etablissement,$connexion){

    $sql = "SELECT niveau.libelle as niveau_etud from niveau join classe on niveau.libelle=classe.niveau join inscription on inscription.classe=classe.libelle where inscription.id=$inscription and inscription.etab='$etablissement'";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['niveau_etud'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
       
}

function getLieuNaissanceCandidat($codeCandidat,$etablissement,$connexion){

    $sql = "SELECT lieu_nais  FROM candidat WHERE code = '$codeCandidat' and etab='$etablissement' ";

    $result = $connexion->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();

       
        return $row['lieu_nais'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }
       
}

function obtenirSpecialiteClasse($classe, $connexion) {
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT specialite FROM classe WHERE libelle = ?");
        $requete->bind_param('s', $classe);
        $requete->execute();


        $specialite=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($specialite);

        // Récupérez les résultats de la requête
        $requete->fetch();

       

        if ($specialite !== null) {
            return  $specialite;
        } else {
            return "classe non trouvé.";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}
function obtenirNiveauClasse($classe, $connexion) {
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT niveau FROM classe WHERE libelle = ?");
        $requete->bind_param('s', $classe);
        $requete->execute();


        $niveau=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($niveau);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($niveau !== null) {
            return  $niveau;
        } else {
            return "classe non trouvé.";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}
function obtenirCodeById($id, $connexion) {
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT candidat FROM inscription WHERE id = ?");
        $requete->bind_param('i', $id);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "etudiant non trouvé.";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}
function estTableVide($connexion, $nomTable) {
    try {
        // Exécutez une requête pour compter le nombre de lignes dans la table
        $resultat = $connexion->query("SELECT COUNT(*) AS total FROM $nomTable");

        // Vérifiez s'il y a une erreur dans la requête
        if (!$resultat) {
            die("Erreur dans la requête : " . $connexion->error);
        }

        // Obtenez le résultat sous forme de tableau associatif
        $row = $resultat->fetch_assoc();

        // Récupérez le total
        $totalLignes = $row['total'];

        // Fermez le résultat
        $resultat->close();

        // Vérifiez si la table est vide
        return $totalLignes == 0;
    } catch (Exception $e) {
        // Gérez les exceptions
        die("Une erreur s'est produite : " . $e->getMessage());
    }
}
function getSpecialiteCandidat($id_user,$connexion)
{
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT specialite FROM candidat WHERE utilisateur = ?");
        $requete->bind_param('i', $id_user);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "vide";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}

function obtenirNiveauParSemestre($semestreTexte) {
    // Extraire le numéro du semestre depuis le texte
    $numeroSemestre = (int) filter_var($semestreTexte, FILTER_SANITIZE_NUMBER_INT);

    switch ($numeroSemestre) {
        case 1:
        case 2:
            return "Première année";
        case 3:
        case 4:
            return "Deuxième année";
        case 5:
        case 6:
            return "Troisième année";
        default:
            return "Semestre invalide";
    }
}



function getNiveauCandidat($id_user,$connexion)
{
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT c.niveau FROM classe as c join inscription as i  on c.libelle=i.classe join candidat as t on i.candidat=t.code WHERE t.utilisateur = ?");
        $requete->bind_param('i', $id_user);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "vide";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}

function getPrenomCandidat($id_user,$connexion)
{
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT prenom FROM candidat WHERE utilisateur = ?");
        $requete->bind_param('i', $id_user);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "vide";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}
function getAnneeC($id_user,$connexion)
{
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT inscription.annee from inscription join candidat on inscription.candidat=candidat.code where utilisateur = ?");
        $requete->bind_param('i', $id_user);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "vide";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}

function getClasseCandidat($id_user,$connexion)
{
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT classe FROM candidat join inscription on candidat.code=inscription.candidat WHERE utilisateur = ?");
        $requete->bind_param('i', $id_user);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "vide";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}

function getEtablissementEtudiant($id_user,$connexion)
{
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT etab FROM inscription WHERE id = ?");
        $requete->bind_param('i', $id_user);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "vide";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}
function getEtablissementCandidat($id_user,$connexion)
{
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT etab FROM candidat WHERE utilisateur = ?");
        $requete->bind_param('i', $id_user);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "vide";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}

function getNomCandidat($id_user,$connexion)
{
    try {
        // Préparez et exécutez la requête SQL
        $requete = $connexion->prepare("SELECT nom FROM candidat WHERE utilisateur = ?");
        $requete->bind_param('i', $id_user);
        $requete->execute();


        $code=null;

        // Liez les résultats de la requête à des variables
        $requete->bind_result($code);

        // Récupérez les résultats de la requête
        $requete->fetch();

       
        if ($code !== null) {
            return  $code;
        } else {
            return "vide";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        echo "Erreur : " . $e->getMessage();
        return "Une erreur s'est produite lors de l'accès à la base de données.";
    }
}

/*
function insererCandidatDansClasse($codeCandidat, $specialite,$annee, $etab,$connexion) {
    try {
        // Vérifier quelles classes de la spécialité ont moins de 10 étudiants déjà inscrits
        $requeteClasses ="SELECT inscription.classe FROM inscription join classe on inscription.classe=classe.libelle WHERE classe.niveau ='Première annee' and annee = '$annee' GROUP BY inscription.classe HAVING COUNT(*) < 10 ";

        $resultat = $connexion->query($requeteClasses);
        
        // Si des classes sont disponibles, insérer le candidat dans la première classe disponible
        if ($resultat->num_rows > 0) {
        
            $row = $resultat->fetch_assoc();
            $classe = $row['classe'];

            // Insérer le candidat dans la classe
            $sql="INSERT INTO inscription (candidat, classe,annee,etab) VALUES ('$codeCandidat', '$classe', '$annee', '".$_SESSION['etablissement']."')";
            if($connexion->query($sql)){
            
                $userIP = $_SERVER['REMOTE_ADDR'];
        
                logUserAction($connexion,$_SESSION['id_user'],"inscription d'un candidat =",date("Y-m-d H:i:s"),$userIP,"valeur enregistrée : $codeCandidat+$classe+$specialite+$annee");
                if(changerStatutCandidat($codeCandidat,$annee,"inscrit",$connexion, $_SESSION['lib_etab'])){

                    return   "Le candidat a été inséré dans la classe $classe de la spécialité $specialite.";
                  }else{
                    return $connexion->error;
                  }
          }else{
              return $connexion->error;
          }
        } else {


              if(estTableVide($connexion,"inscription")){
                   $classe1=trouverClasseParSpecialite($connexion,$specialite);

                   $sql = "INSERT INTO inscription (candidat, classe,annee,etab) VALUES ('$codeCandidat', '$classe1', '$annee' ,'".$_SESSION['etablissement']."')";
                   

                   if($connexion->query($sql)){
                    $userIP = $_SERVER['REMOTE_ADDR'];
        
                    logUserAction($connexion,$_SESSION['id_user'],"inscription d'un candidat =",date("Y-m-d H:i:s"),$userIP,"valeur enregistrée : $codeCandidat+$classe1+$specialite+$annee");
      
                  if(changerStatutCandidat($codeCandidat,$annee,"inscrit",$connexion,$_SESSION["lib_etab"])){

                    return   "Le candidat a été inséré dans la classe $classe1 de la spécialité $specialite.";
                  }else{
                    return $connexion->error;
                  }
                   

                   }else{

                       return $connexion->error;
                   }
                
                
                  

                   
             }else{
                 return "Aucune classe disponible pour la spécialité $specialite.";

             }
     }
        
    }catch (Exception $e) {
        // Gérer les exceptions
        return "Une erreur s'est produite : " . $e->getMessage();
    }
}*/
function genererMatriculeEnseignant($prenom, $nom) {
    // Obtenir la première lettre du prénom et du nom
    $initialePrenom = strtoupper(substr($prenom, 0, 1));
    $initialeNom = strtoupper(substr($nom, 0, 1));

    // Générer un nombre aléatoire à trois chiffres
    $nombreAleatoire = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

    // Obtenir l'année actuelle à deux chiffres
    $anneeActuelle = date('y');

    // Construire le matricule
    $matricule = $initialePrenom . $initialeNom . $nombreAleatoire . $anneeActuelle;

    return $matricule;
}

function getNomEnseignant($code , $connexion){

            $sql = "select nom from enseignant where code='$code'";
            $resultat =$connexion->query($sql);

            $nom = null;

            while($ens = $resultat->fetch_assoc()){

                $nom = $ens['nom'];
            }

            if ( $nom !== null){
                return $nom;
            }else{

                return " aucun nom ";
            }

}

function getNomMois($numeroMois) {
    $mois = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];

    // Vérifier si le numéro du mois est valide
    if (array_key_exists($numeroMois, $mois)) {
        return $mois[$numeroMois];
    } else {
        return 'Mois invalide';
    }
}

function getCodeEnseignant($id,$connexion)
{
    $sql = "select code from enseignant where id=$id";
    $resultat =$connexion->query($sql);

$nom=null;
    while($ens = $resultat->fetch_assoc()){

        $nom = $ens['code'];
    }

   return $nom;

}


function getEtablissementEnseignement($id,$connexion){

    $sql ="select etab from enseignant where id=$id";
    $resultat =$connexion->query($sql);
    $s=null;
    while($ens = $resultat->fetch_assoc()){

        $s = $ens['etab'];
    }
    return $s;
}

function getsexeEnseignantById($id,$connexion){

    $sql ="select sexe from enseignant where id=$id";
    $resultat =$connexion->query($sql);
    $s=null;
    while($ens = $resultat->fetch_assoc()){

        $s = $ens['sexe'];
    }
    return $s;
}

function getDiplomeEnseignant($id,$connexion){
    $sql = "select diplome from enseignant where id=$id";
    $resultat =$connexion->query($sql);

$s=null;
    while($ens = $resultat->fetch_assoc()){

        $s = $ens['diplome'];
    }

   return $s;
}


function getEnseignantDescription($enseignantId,$conn) {
   $diplome =null; $domaine=null;
    $sql = "SELECT diplome, specialite FROM enseignant WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $enseignantId);
    $stmt->execute();
    $stmt->bind_result($diplome, $domaine);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    // Vérifiez et construisez la chaîne de retour
    if ($diplome && $domaine) {
        if (($diplome) == 'Doctorat unique') {
            return "Docteur en " . $domaine;
        } elseif (($diplome) == 'master') {
            return "Master en " . $domaine;
        } else {
            return ucfirst($diplome) . " en " . $domaine;
        }
    } else {
        return "Informations de l'enseignant non trouvées.";
    }
}

function getDateNaissanceEnseignant($id_enseignant,$connexion){


    $sql = "select date_naissance from enseignant where id=".$id_enseignant;
    $resultat =$connexion->query($sql);

    $d=null;
    if ($resultat) {
        $row = $resultat->fetch_assoc();

        return $row['date_naissance'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}

function getVilleEnseignant($id_enseignant,$connexion){


    $sql = "select ville from enseignant where id=".$id_enseignant;
    $resultat =$connexion->query($sql);

    $d=null;
    if ($resultat) {
        $row = $resultat->fetch_assoc();

       
        return $row['ville'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}


function getTelEnseignant($id_enseignant,$connexion){


    $sql = "select telephone from enseignant where id=".$id_enseignant;
    $resultat =$connexion->query($sql);

    $d=null;
    if ($resultat) {
        $row = $resultat->fetch_assoc();

       
        return $row['telephone'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}

function getEmailEnseignant($id_enseignant,$connexion){


    $sql = "select email from enseignant where id=".$id_enseignant;
    $resultat =$connexion->query($sql);

    $d=null;
    if ($resultat) {
        $row = $resultat->fetch_assoc();

       
        return $row['email'] ;
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    }

}

function getEnseignantByContrat($enseignant,$connexion){
    $sql = "select id from enseignant where contrat='$enseignant'";
    $resultat =$connexion->query($sql);

$nom=null;
    while($ens = $resultat->fetch_assoc()){

        $nom = $ens['id'];
    }

   return $nom;

}
function getContratEnseignant($enseignant,$connexion){
    $sql = "select contrat from enseignant where code='$enseignant'";
    $resultat =$connexion->query($sql);

$nom=null;
    while($ens = $resultat->fetch_assoc()){

        $nom = $ens['contrat'];
    }

   return $nom;

}

function getEcuesContrat($numero_contrat,$etab,$connexion){

    $sql="select ecue from contrat_couverture where contrat='$numero_contrat' and etab='$etab'";
    $requete =$connexion->query($sql);

   $data=array();

   while($d=$requete->fetch_assoc()){

     $data[] = $d["ecue"];
   }

    return $data;

}

function getNomPrenomEnseignantById($code , $connexion){
    $sql = "select nom,prenom from enseignant where id=$code";
    $resultat =$connexion->query($sql);

    $nom = null;

    while($ens = $resultat->fetch_assoc()){

        $nom = $ens['nom']. " ".$ens['prenom'];
    }

    if ( $nom !== null){
        return strtolower($nom);
    }else{

        return "aucun";
    }
}
function getNomPrenomEnseignant($code , $connexion){
    $sql = "select nom,prenom from enseignant where code='$code'";
    $resultat =$connexion->query($sql);

    $nom = null;

    while($ens = $resultat->fetch_assoc()){

        $nom = $ens['nom']. " ".$ens['prenom'];
    }

    if ( $nom !== null){
        return strtolower($nom);
    }else{

        return "aucun";
    }
}

function verifierInput($heef,$annee,$mois,$enseignant,$connexion,$etab)
{
    $sql ="select * from cumuleHeure where enseignant='$enseignant' and mois='$mois' and annee='$annee' and etab='$etab'";
   

    $result = $connexion->query($sql);
    if ($result) {
       $row = $result->fetch_assoc();
   
       return $row['total_heures'] == $heef;
   } else {
       // Gestion de l'erreur
       die("Erreur lors de la requête SQL : " . $connexion->error);
   }


}


function verifierNotesLicence($id_etudiant, $connexion,$etablissement) {
    // Vérifier s'il existe des notes pour tous les semestres de la licence pour cet étudiant
    $query = "SELECT COUNT(*) AS count FROM notation WHERE inscription =$id_etudiant and semestre in ('semestre 1','semestre 2','semestre 3','semestre 4','semestre 5','semestre 6') and etab='$etablissement'";
    $stmt = $connexion->query($query);
   
    if ($stmt->num_rows > 0) {
        return true; // Tous les six semestres sont couverts
    } else {
        return false; // Certains semestres manquent
    }
}

function verifierSoutenanceEtudiant($id_etudiant, $connexion,$etablissement) {
    // Vérifier s'il existe des notes pour tous les semestres de la licence pour cet étudiant
    $sql = "SELECT code FROM soutenance WHERE impetrant =$id_etudiant and  etab='$etablissement'";
    $stmt = $connexion->query($sql);
    if ( $stmt->num_rows > 0) {
        return true; // Tous les six semestres sont couverts
    } else {
        return false; // Certains semestres manquent
    }
}

function getEtudiantByNumeroDemande($demande,$etab,$connexion){
    
    $sql ="select  etudiant from demande where code='$demande' and etab='$etab'";
     $stmt = $connexion->query($sql);
    if ( $stmt->num_rows > 0) {
       
        $resultat =$stmt->fetch_assoc();
        $t = $resultat['etudiant'];
        return $t;
    } else {
        return null; 
    }
}

function getTypeDemandeByNumeroDemande($demande,$etab,$connexion){
    
    $sql ="select  diplome from demande where code='$demande' and etab='$etab' ";
     $stmt = $connexion->query($sql);
    if ( $stmt->num_rows > 0) {
       
        $resultat =$stmt->fetch_assoc();
        return $resultat["diplome"];
    } else {
        return null; 
    }
}





function utilisateurDateLimiteDepassee($connexionBD, $idUtilisateur) {
    // Date d'aujourd'hui
    $dateAujourdhui = date('Y-m-d');
    
    // Requête SQL pour vérifier si l'utilisateur a dépassé sa date limite
    $requete = "SELECT COUNT(*) AS count FROM utilisateur WHERE id = $idUtilisateur AND date_debut > '$dateAujourdhui' AND date_fin < '$dateAujourdhui'";
    
    // Exécution de la requête
    $resultat = mysqli_query($connexionBD, $requete);
    
    // Vérification du résultat
    if ($resultat ->num_rows > 0) {
        $row = mysqli_fetch_assoc($resultat);
        $count = $row['count'];
        
        // Si au moins une ligne correspond à la condition, la date limite est dépassée
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        // En cas d'erreur dans la requête SQL
        echo "Erreur dans la requête : " . mysqli_error($connexionBD);
        return false;
    }
}

function modifierNoteDevoir($etudiant,$ecue,$semestre,$annee,$etab,$note,$connexion){


    $sql="select id from ligne2 where etudiant='$etudiant' and code_ecue='$ecue' and semestre='$semestre' and annee='$annee' and etab='$etab' limit 1";

    $r = $connexion->query($sql);

    if($r){

     while($data = $r->fetch_object()){
         $id_note = $data->id;
     }


       if($connexion->query("update ligne2 set note=$note where id=$id_note")){


        $nouvelleNote=moyenneGeneraleDevoirs($connexion,$etudiant,$ecue,$semestre,$annee);
        $id_notation =verifierInscriptionNotation($connexion,$etudiant,$ecue,$semestre,getClasseByInscription($etudiant,$connexion),$annee);
       if($id_notation !== null){

          if($nouvelleNote !== null)
          {
            $sql1="UPDATE notation set moyDev=$nouvelleNote where id=$id_notation";
             }else{
                $sql1="UPDATE notation set moyDev=NULL where id=$id_notation";
             }

          if($connexion->query($sql1)){
            
               return true;
          }else{
            false;
         }

          

         
       }else{
       false;
       }

       




    }else{

        return $connexion->error;
    }

    }
}

function modifierNoteExamen($etudiant,$ecue,$semestre,$annee,$etab,$note,$type,$connexion){


    $sql="select id from ligne2 where etudiant='$etudiant' and code_ecue='$ecue' and semestre='$semestre' and annee='$annee' and etab='$etab' limit 1";

    $r = $connexion->query($sql);

    if($r){

     while($data = $r->fetch_object()){
         $id_note = $data->id;
     }


       if($connexion->query("update ligne2 set note=$note where id=$id_note")){


        $nouvelleNote=moyenneGeneraleDevoirs($connexion,$etudiant,$ecue,$semestre,$annee);
        $id_notation =verifierInscriptionNotation($connexion,$etudiant,$ecue,$semestre,getClasseByInscription($etudiant,$connexion),$annee);
       if($id_notation !== null){

          if($nouvelleNote !== null)
          {
            $sql1="UPDATE notation set moyDev=$nouvelleNote where id=$id_notation";
             }else{
                $sql1="UPDATE notation set moyDev=NULL where id=$id_notation";
             }

          if($connexion->query($sql1)){
            
               return true;
          }else{
            false;
         }

          

         
       }else{
       false;
       }

       




    }else{

        return $connexion->error;
    }

    }
}

function getGradeById($codeEnseignant,$conn) {
 
    // Requête SQL pour obtenir le grade et le th correspondant le plus récent
    $sql = "SELECT grade,sexe
            FROM enseignant
            WHERE id=$codeEnseignant";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupération des données
        $row = $result->fetch_assoc();
        $grade = $row["grade"];

        if($grade == "Aucun" and $row["sexe"] == "Homme"){
            $grade ="Monsieur";
        }else if($grade == "Aucun" and $row["sexe"] == "Femme"){
            $grade ="Madame";
        }
      

      

        return $grade;
    } else {
       

        return "null"; // Ou une valeur par défaut selon vos besoins
    }
}

function getGrade($codeEnseignant,$conn) {
 
    // Requête SQL pour obtenir le grade et le th correspondant le plus récent
    $sql = "SELECT grade
            FROM enseignant
            WHERE code='$codeEnseignant'
           ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupération des données
        $row = $result->fetch_assoc();
        $grade = $row["grade"];
      

      

        return $grade;
    } else {
       

        return "null"; // Ou une valeur par défaut selon vos besoins
    }
}


function getPrenomEnseignant($code , $connexion){
    $sql = "select prenom from enseignant where code='$code'";
    $resultat =$connexion->query($sql);

    $nom = null;

    while($ens = $resultat->fetch_assoc()){

        $nom = $ens['prenom'];
    }

    if ( $nom !== null){
        return $nom;
    }else{

        return " aucun prenom ";
    }

}

function trouverClasseParSpecialite($connexion, $specialite) {
    try {
        // Exécutez une requête pour récupérer le nom de la première classe correspondante
        $requete = $connexion->prepare("SELECT libelle FROM classe WHERE specialite = ? LIMIT 1");
        $requete->bind_param('s', $specialite);
        $requete->execute();

        // Vérifiez s'il y a une erreur dans la requête
        if (!$requete) {
            die("Erreur dans la requête : " . $connexion->error);
        }

        // Obtenez le résultat sous forme de tableau associatif
        $resultat = $requete->get_result();
        $row = $resultat->fetch_assoc();

        // Fermez le résultat
        $resultat->close();

        // Vérifiez si une classe a été trouvée
        if ($row) {
            return $row['libelle'];
        } else {
            return "Aucune classe trouvée pour la spécialité $specialite.";
        }
    } catch (Exception $e) {
        // Gérez les exceptions
        die("Une erreur s'est produite : " . $e->getMessage());
    }
}


function changerStatutCandidat($codeCandidat, $annee, $nouveauStatut, $connexion,$etab) {

    $requete = "UPDATE candidat SET statut = '$nouveauStatut' WHERE code='$codeCandidat' and annee= '$annee' and etab='$etab'";
    if ($connexion->query($requete)) {
        return true;
    } else {
       
        return false;
    }
}



function modifierEnseignant($codeEnseignant, $nouveauDiplome, $nouveauTauxHoraire, $nouveauGrade, $connexion) {
    // Échapper les variables pour éviter les attaques SQL par injection
    $codeEnseignant = clean_input($codeEnseignant);
    $nouveauDiplome = clean_input($nouveauDiplome);
    $nouveauTauxHoraire = clean_input($nouveauTauxHoraire);
    $nouveauGrade = clean_input($nouveauGrade);

    // Construire la requête SQL
    $requete = "UPDATE enseignant SET diplome = '$nouveauDiplome', th = '$nouveauTauxHoraire', grade = '$nouveauGrade' WHERE code = '$codeEnseignant'";

    // Exécuter la requête
    $resultat = mysqli_query($connexion, $requete);

    if ($resultat) {
        // Vérifier si des lignes ont été affectées (mise à jour réussie)
        if (mysqli_affected_rows($connexion) > 0) {
            
            return true;
        } else {
            
            return false;
        }
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
        return false;
    }
}



function verifierAffectationEnseignant($codeEnseignant, $codeECUE, $annee, $semestre, $pdo, $classe, $etab) {
    try {
        // Préparer la requête SQL avec des paramètres
        $requete = "
            SELECT COUNT(*) AS nombreAffectations 
            FROM repartition_enseignant 
            WHERE code = :codeEnseignant 
              AND code_ecue = :codeECUE 
              AND annee = :annee 
              AND semestre = :semestre 
              AND classe = :classe 
              AND etab = :etab
        ";

        // Préparer la requête
        $stmt = $pdo->prepare($requete);

        // Lier les paramètres pour éviter les injections SQL
        $stmt->bindParam(':codeEnseignant', $codeEnseignant, PDO::PARAM_STR);
        $stmt->bindParam(':codeECUE', $codeECUE, PDO::PARAM_STR);
        $stmt->bindParam(':annee', $annee, PDO::PARAM_STR);
        $stmt->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $stmt->bindParam(':classe', $classe, PDO::PARAM_STR);
        $stmt->bindParam(':etab', $etab, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Récupérer le résultat
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'enseignant a été affecté (nombre d'affectations > 0)
        return isset($result['nombreAffectations']) && $result['nombreAffectations'] > 0;

    } catch (PDOException $e) {
        // Gérer les erreurs PDO
        error_log("Erreur de requête PDO : " . $e->getMessage());
        return false;
    }
}


function verifierAffectationEnseignant2($codeEnseignant, $annee, $etablissement, $connexion) {
    // Échapper les variables pour éviter les attaques SQL par injection

    // Construire la requête SQL
    $requete = "SELECT COUNT(*) AS nombreAffectations FROM affectation WHERE enseignant ='$codeEnseignant' AND  annee ='$annee' AND etab ='$etablissement'";

    // Exécuter la requête
    $resultat = mysqli_query($connexion, $requete);

    if ($resultat) {
        // Récupérer le résultat de la requête
        $row = mysqli_fetch_assoc($resultat);

        // Vérifier si l'enseignant a été affecté (nombre d'affectations > 0)
        return $row['nombreAffectations'] > 0;
    } else {
        // Gérer les erreurs de requête
        echo "Erreur de requête : " . mysqli_error($connexion);
       
    }
}

function obtenirSemestrePourECUE($nomECUE, $connexion) {
    // Construire la requête SQL
    $sql ="SELECT semestre FROM ue WHERE code in ( select code_ue from ecue where code_ecue='".$nomECUE."')";
    $resultat =$connexion->query($sql);
    $nom = null;
    while($ens = $resultat->fetch_assoc()){
        $nom = $ens['semestre'];
    }
    if ( $nom !== null){
        return $nom;
    }else{
        return " aucun semestre ";
    }
}


function getCandidatCodeByInscription($id, $connexion) {


    // Construire la requête SQL
    $sql ="SELECT candidat FROM inscription WHERE id=$id";

   
    $resultat =$connexion->query($sql);

    $candidat = null;

    while($ens = $resultat->fetch_assoc()){

        $candidat = $ens['candidat'];
    }

    if ( $candidat !== null){
        return $candidat;
    }else{

        return "aucun candidat";
    }
}
function getCreditByLibelleUe($libelleUe, $conn) {

    $libelleUe= mysqli_real_escape_string($conn,$libelleUe);
    $query = "SELECT sum(credit) as somme FROM ecue WHERE ue ='".$libelleUe."'";
    $t=null;

    $result= $conn->query($query);

     while($d = $result->fetch_assoc())
     {
       $t=$d['somme'];
     } 
     
     if($t !== null){
        return $t;
     }else{
        return "error";
     }
}

function moyenneUE($etudiant,$semestre,$annee,$ue,$conn){
   

    $sql=" select libelle from ecue where ue='".$ue."'";

    $ecue = array();

    $result=$conn->query($sql);

    while($data= $result->fetch_assoc())
    {
        $ecue []=$data['libelle'];
    }


    $sommeMoyenneEcue = array();

    foreach ($ecue as $e) {

    $sql="select moyGen,credit from notation join ecue on notation.ecue=ecue.libelle where inscription=".$etudiant." and notation.semestre='".$semestre."' and notation.annee='".$annee."' and ecue='".$e."'";
       
    if( $conn->query($sql)){
        $resultat=$conn->query($sql);
    if(mysqli_num_rows($resultat) > 0){
        while($data=$resultat->fetch_assoc()){

            $sommeMoyenneEcue [] = $data['moyGen']*$data['credit'];
        }
        $somme1=0;
    
        foreach($sommeMoyenneEcue as $s){
    
          $somme1=$somme1 + $s;
        }
    
    
        $somme2 = getCreditByLibelleUe($ue,$conn);
    
    
        $moyenneUe= $somme1/$somme2;
    
    
        return round($moyenneUe,2);
    }
    else{

        return "none";
    }}else{
        return "vide";
    }
}



}


function verifierEliminatoire($tableau) {
    // Parcourir le tableau
    
    $b= false;
    foreach ($tableau as $element) {
        // Vérifier si l'élément est inférieur à 6
        if ($element < 6) {
            $b= true; // Retourne vrai si un élément inférieur à 6 est trouvé
        }
    }
    return $b; // Retourne faux si aucun élément inférieur à 6 n'est trouvé
}



function rechercher_notes_eliminatoires($etudiant_id, $semestre, $annee, $conn) {
    $ecues_eliminatoires = array();

    try {
        $ecues_eliminatoires=null;
        $sql = "SELECT ecue
                FROM notation
                WHERE inscription = ? AND semestre = ? AND annee = ? AND  ((moyEx+moyDev)/2) < 6";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $etudiant_id, $semestre, $annee);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $ecues_eliminatoires[] = $row['ecue'];
        }

        return $ecues_eliminatoires;
    } catch (Exception $e) {
         echo "Erreur : " . $e->getMessage();
       
    }
}


function calcul_moyenne($etudiant_id, $semestre, $annee,$etab, $db) {
    try {
        $query= "SELECT SUM(moyenneUE) AS total_ue, COUNT(distinct(ue)) AS nb_ue
                FROM vue_moyenne_ue
                WHERE inscription =$etudiant_id AND semestre = '$semestre' AND annee = '$annee' and etab ='$etab'";

        $result = $db->query($query);

        while($data=$result->fetch_assoc()){
            if ($data['nb_ue'] == 0) {
                return "-";
            }
    
            $moyenne_classe = round($data['total_ue'] / $data['nb_ue'],2);
            return $moyenne_classe;
            
        }
      
       

      
    } catch (Exception $e) {
     echo "Erreur : " . $e->getMessage();
       
    }
}

function getUeBySem($libelleEcue, $conn) {
    $query = "SELECT semestre  FROM ue where code  = ?";
    
    // Préparation de la requête
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $libelleEcue);
    
    // Exécution de la requête
    $stmt->execute();
    $credit = null;
    // Liaison du résultat
    $stmt->bind_result($credit);
    
    // Récupération du résultat
    if ($stmt->fetch()) {
        return $credit;
    } else {
        return null;
    }
    
    // Fermeture de la requête
    $stmt->close();
}
function getUeByEcue($libelleEcue, $conn) {
    $query = "SELECT libelle  FROM ue where libelle in ( select ue from ecue WHERE libelle = ?)";
    
    // Préparation de la requête
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $libelleEcue);
    
    // Exécution de la requête
    $stmt->execute();
    $credit = null;
    // Liaison du résultat
    $stmt->bind_result($credit);
    
    // Récupération du résultat
    if ($stmt->fetch()) {
        return $credit;
    } else {
        return null;
    }
    
    // Fermeture de la requête
    $stmt->close();
}
function getCreditByLibelleEcue($libelleEcue, $conn) {
    $query = "SELECT credit FROM ecue WHERE libelle = ?";
    
    // Préparation de la requête
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $libelleEcue);
    
    // Exécution de la requête
    $stmt->execute();
    $credit = null;
    // Liaison du résultat
    $stmt->bind_result($credit);
    
    // Récupération du résultat
    if ($stmt->fetch()) {
        return $credit;
    } else {
        return null;
    }
    
    // Fermeture de la requête
    $stmt->close();
}


function eleveDansClasse($candidat, $classe, $annee,$connexion) {


    // Requête SQL pour vérifier si l'élève est dans la classe au cours de l'année spécifiée
    $requete = "SELECT COUNT(*) AS count_eleve
                FROM inscription
                WHERE candidat = '$candidat'
                AND classe = '$classe'
                AND annee = '$annee'";

    // Exécution de la requête
    $resultat = $connexion->query($requete);

    // Vérification des résultats
    if ($resultat) {
        // Récupération du nombre de lignes
        $row = $resultat->fetch_assoc();
        $nombreEleves = $row['count_eleve'];

        // Retourne true si l'élève est dans la classe au cours de l'année spécifiée, sinon false
        return ($nombreEleves > 0);
    } else {
        // En cas d'erreur lors de l'exécution de la requête
        die("Erreur lors de l'exécution de la requête : " . $connexion->error);
    }
}
function getEcuesForUE($id_ue,$inscription, $connexion,$semestre) {
    // Requête SQL pour récupérer les ECUEs pour une UE donnée par ordre alphabétique
    $requete = "SELECT moyGen,credit FROM ue join ecue on ue.libelle=ecue.ue join notation on notation.ecue=ecue.libelle WHERE ecue.ue= '$id_ue' and notation.inscription=$inscription and ue.semestre='$semestre' ORDER BY ecue.libelle ASC";
    // Exécution de la requête
    $resultat = $connexion->query($requete);

    // Vérification des résultats
    if ($resultat) {
        // Tableau pour stocker les ECUEs
        $ecues = [];

        // Récupération des données de la requête
        while ($row = $resultat->fetch_assoc()) {
            $ecues[] = $row;
        }

        return $ecues;
    } else {
        // En cas d'erreur lors de l'exécution de la requête
        die("Erreur lors de la récupération des données : " . $connexion->error);
    }
}


function calculMoyenneUE($ue,$inscription,$connexion,$semestre) {


    $ecues =getEcuesForUE($ue,$inscription,$connexion,$semestre);
    $moyennesEcue = array();
    $coefficientsEcue = array();

    foreach ($ecues as $ecue) {
      $moyennesEcue =$ecue['moyGen'];
      $coefficientsEcue=$ecue['credit'];
    }
        
    // Vérification des tableaux de même longueur
    if (count($moyennesEcue) !== count($coefficientsEcue)) {
        return 0; 
        exit;// Ou une valeur appropriée pour signaler une erreur
    }

    // Calcul de la moyenne pondérée de l'UE
    $sommeMoyennesPonderees = 0;
    $sommeCoefficients = 0;

    for ($i = 0; $i < count($moyennesEcue); $i++) {
        $sommeMoyennesPonderees += $moyennesEcue[$i] * $coefficientsEcue[$i];
        $sommeCoefficients += $coefficientsEcue[$i];
    }

    // Vérification pour éviter une division par zéro
    if ($sommeCoefficients == 0) {
        return 0; 
        exit;// Ou une valeur appropriée pour signaler une erreur
    }


    $moyenneUE=0;
    // Calcul de la moyenne pondérée de l'UE
    $moyenneUE = $sommeMoyennesPonderees / $sommeCoefficients;

    return $moyenneUE;
}


function get_statut_paiement_concours($candidat,$annee,$connexion){
    $sql = "SELECT statut_paiement_concours FROM candidat WHERE code='$candidat' and annee='$annee'";
    $result = $connexion->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['statut_paiement_concours'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    } 
}

function getIdInscription($candidat,$annee,$connexion){
    $sql = "SELECT id FROM inscription WHERE candidat='$candidat' and annee='$annee'";
    $result = $connexion->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    } else {
       return null;
    } 
}

function getIdInscriptionFromAnonymat($code,$annee,$connexion){
    $sql = "SELECT etudiant FROM anonymat WHERE numero='$code' and annee='$annee'";
    $result = $connexion->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['etudiant'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    } 
}

function get_statut_admission_concours($candidat,$annee,$connexion){
    $sql = "SELECT statut_admission_concours FROM candidat WHERE code='$candidat' and annee='$annee'";
    $result = $connexion->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['statut_admission_concours'];
    } else {
        // Gestion de l'erreur
        die("Erreur lors de la requête SQL : " . $connexion->error);
    } 
}


function alterStatutCandidat($candidat,$connexion,$statut,$type,$annee,$etab){
    $sql="";

    if($type =="statut_paiement"){

          $sql="UPDATE candidat set statut_paiement=$statut where code='$candidat' and annee='$annee' and etab='$etab'";
          if($connexion->query($sql)){

            return true;
          }else{
              return false;
          }
    }else if($type =="statut_paiement_concours"){
        $sql="UPDATE candidat set statut_paiement_concours=$statut where code='$candidat' and annee='$annee' and etab='$etab'";
        if($connexion->query($sql)){

            return true;
          }else{
              return false;
          }

    }else if($type =="statut")
    {
        $sql="UPDATE candidat set statut=$statut where code='$candidat' and annee='$annee' and etab='$etab'";
        if($connexion->query($sql)){

            return true;
          }else{
              return false;
          }
    }

   

}
function getSpecialiteDuCandidat($codeCand,$annee,$connexion){


    $sql ="select specialite from candidat where code='$codeCand' and annee='$annee'";

    $result = $connexion->query($sql);

    if ($result->num_rows > 0) {
        // Récupération du nombre total de cours
        $row = $result->fetch_assoc();
   


        return $row['specialite'];
    } else {
      

        return "aucun";
    }
}
function typeEtablissement($etab,$connexion){


    $sql ="select type from etablissement where libelle='$etab'";

    $result = $connexion->query($sql);

    if ($result->num_rows > 0) {
        // Récupération du nombre total de cours
        $row = $result->fetch_assoc();
   


        return $row['type'];
    } else {
      

        return "aucun";
    }
}

function typeEtablissementCandidature($code,$connexion,$annee){


    $sql ="select type from etab where libelle in ( select etab from candidat where code='$code' and annee='$annee')";

    $result = $connexion->query($sql);

    if ($result->num_rows > 0) {
        // Récupération du nombre total de cours
        $row = $result->fetch_assoc();
   


        return $row['type'];
    } else {
      

        return "aucun";
    }
}

function aFaitCoursCeMois($numeroMois, $codeEnseignant, $annee,$conn,$etab) {
  
    // Requête SQL pour vérifier si l'enseignant a dispensé des cours ce mois-ci
    $sql = "SELECT COUNT(*) as totalCours
            FROM cours
            WHERE MONTH(date_c) = '$numeroMois'
            AND annee = '$annee'
            AND enseignant = '$codeEnseignant' and etab='$etab'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupération du nombre total de cours
        $row = $result->fetch_assoc();
        $totalCours = $row["totalCours"];


        return $totalCours > 0;
    } else {
      

        return false;
    }
}

// Fonction pour calculer la moyenne générale des devoirs d'un étudiant
function moyenneGeneraleDevoirs($connexion, $etudiant, $ecue, $semestre, $annee) {

    // Requête SQL pour récupérer les notes des devoirs de l'étudiant dans l'ECUE spécifié, pour le semestre et l'année spécifiés
    $requete = "SELECT AVG(note) AS moyenne FROM ligne2 WHERE etudiant =$etudiant AND code_ecue = '$ecue' AND semestre = '$semestre' AND annee = '$annee'";

    // Exécution de la requête
    $resultat = $connexion->query($requete);

    // Vérification du résultat de la requête
    if ($resultat->num_rows > 0) {
        // Récupération de la moyenne générale des devoirs
        $row = $resultat->fetch_assoc();
        $moyenne = $row["moyenne"];
    } else {
        $moyenne = 0; // Si aucune note n'est trouvée, la moyenne est considérée comme 0
    }

    // Retourne la moyenne générale des devoirs
    return $moyenne;
}

// Fonction pour récupérer la note de l'examen en fonction du numéro d'anonymat, du type d'examen, de l'année et du semestre
function noteExamen($connexion, $numero_anonymat, $type_examen, $annee) {

    // Requête SQL pour récupérer la note de l'examen
    $requete = "SELECT note FROM ligne1 WHERE anonymat= '$numero_anonymat' AND type_examen = '$type_examen' AND annee = '$annee'";
    // Exécution de la requête
    $resultat = $connexion->query($requete);

    // Vérification du résultat de la requête
    if ($resultat->num_rows > 0) {
        // Récupération de la note de l'examen
        $row = $resultat->fetch_assoc();
        $note_examen = $row["note"];
    } else {
        $note_examen = null; // Si aucune note n'est trouvée, on retourne null
    }

    // Retourne la note de l'examen
    return $note_examen;
}


// Fonction pour calculer la moyenne de l'UE pour un étudiant, une année, un semestre et une UE donnés
function Calcul_moyenne_UE($connexion, $numero_etudiant, $annee, $semestre, $ue) {
    // Récupérer tous les ECUEs liées à l'UE
    $requete_ecues = "SELECT libelle FROM ecue WHERE code_ue = '$ue'";
    $resultat_ecues = $connexion->query($requete_ecues);
    
    $moyenne_ue = 0;
    $nombre_ecues = 0;

    // Pour chaque ECUE, calculer la moyenne des devoirs
    while ($row_ecues = $resultat_ecues->fetch_assoc()) {
        $ecue = $row_ecues['libelle'];

        // Calculer la moyenne des devoirs pour chaque ECUE
        $moyenne_devoirs = moyenneGeneraleDevoirs($connexion, $numero_etudiant, $ecue, $semestre, $annee);
        // Récupérer la note de la session ordinaire pour chaque ECUE
        $note_examen = noteExamen($connexion, $numero_etudiant, 'Session ordinaire', $annee);
        // Si la moyenne des devoirs et la note de l'examen sont disponibles, calculer la moyenne de l'ECUE
        if ($moyenne_devoirs !== null && $note_examen !== null) {
            $moyenne_ue += ($moyenne_devoirs + $note_examen) / 2;
            $nombre_ecues++;
        }
    }
    // Calculer la moyenne de l'UE en divisant la somme des moyennes des ECUEs par le nombre total d'ECUEs
    if ($nombre_ecues > 0) {
        $moyenne_ue /= $nombre_ecues;
    }

    // Retourner la moyenne de l'UE
    return $moyenne_ue;
}



function verifierInscriptionNotation($connexion, $inscription, $ecue, $semestre, $classe, $annee) {
    // Préparation de la requête SQL pour vérifier l'existence de l'inscription
    $requete = "SELECT id FROM notation WHERE inscription =$inscription AND code_ecue='$ecue' AND semestre = '$semestre' AND classe = '$classe' AND annee = '$annee'";

    // Exécution de la requête
    $resultat = $connexion->query($requete);

    // Vérification du résultat de la requête
    if ($resultat->num_rows > 0) {
        // Récupération de l'ID de l'inscription
        $row = $resultat->fetch_assoc();
        $id_inscription = $row["id"];
    } else {
        $id_inscription = 0; // Si l'inscription n'est pas trouvée, on retourne null
    }

    // Retourne l'ID de l'inscription (ou null si non trouvé)
   return $id_inscription;
}




?>












