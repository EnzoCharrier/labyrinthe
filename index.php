<!DOCTYPE html>		
    <html lang= "fr">

    <head>
	    <meta charset= "utf-8">	
	    <title>Labyrinthe</title>
        <link rel="stylesheet" href="style/style.css">
    </head>
    
    
    <body>
        

        <?php 
        session_start();
      
        include("include/header.php");
	    include("include/nav.php");
            
      
        ////////      *Rajouter orientation, retester clé,rebosser btn reset et faire css/ergonomie        */////////


            $bdd_fichier = 'labyrinthe.db';	
	        $type = "depart";
            $typeSortie = "sortie";
            $cle = "cle";
            $nbCle = 0;
            $empl = $_GET['couloir'];
            $dir = "";

            // Ouvrir Base de Données
	        $sqlite = new SQLite3($bdd_fichier);

            // Debut Requete 
            $initial='SELECT couloir.id, couloir.type FROM couloir WHERE type=:type'; //requete emplacement initial
            $requeteINIT = $sqlite -> prepare($initial);	                //
	        $requeteINIT -> bindValue(':type', $type, SQLITE3_TEXT);        // Execution requete 
	        $resultINIT = $requeteINIT -> execute();                        //


            // Recuperer la valeur de l'id de la position de depart
            while($requeteINIT = $resultINIT -> fetchArray(SQLITE3_ASSOC)) {
                    
                  $init = $requeteINIT['id'];                   
            }

            // Bouton Reset permet de revenir a la position de depart
            if ($empl == -1) {

                $empl = $init;
                $_SESSION['nbCle'] = 0;
                unset($_SESSION["Score"]);
                $_SESSION["nbCle"] = 0;
            }
                
            if ($empl == 0) {

                include("pages/page0.php");
                echo "<h1> <a href ='index.php?couloir=".$init."'> Jouer</a></h1>";
                unset($_SESSION["Score"]);
                $_SESSION["nbCle"] = 0;
            }

            else {    

                if (isset($empl) == $init)
                {
                    // Verifier si le type = sortie et change de page dans ce cas
                    $verifPartie='SELECT couloir.id,couloir.type FROM couloir WHERE couloir.type =:typeSortie';
                    $requeteVerif = $sqlite -> prepare($verifPartie);	
	                $requeteVerif -> bindValue(':typeSortie', $typeSortie, SQLITE3_TEXT);
	                $resultVerif = $requeteVerif -> execute();

                    while($requeteVerif = $resultVerif -> fetchArray(SQLITE3_ASSOC)) {

                        // Gestion de score de deplacement
                        if (isset($_SESSION['Score'])){

                            $_SESSION['Score'] ++;
                        }
                        else
                        {
                            $_SESSION['Score'] = 0;
                        }

                        //echo $_SESSION['Score'];


                        if ($empl == $requeteVerif['id']){

                            include("pages/pageFin.php");
                            echo "<h1><a href ='index.php?couloir=".$init."'>  Recommencer</a></h1>";
                            echo " <h1> Score :".$_SESSION['Score']."</h1>";
                            unset($_SESSION["Score"]);
                        }

                        else
                        {

                            $posCle='SELECT count(id), type type FROM couloir WHERE type=:cle'; // requete pour savoir le nombre de clé presente dans le labyrinthe
                            $requetePosCle = $sqlite -> prepare($posCle);	
	                        $requetePosCle -> bindValue(':cle', $cle,SQLITE3_TEXT);
	                        $resultPosCle = $requetePosCle -> execute();
                        

                            while($requetePosCle = $resultPosCle -> fetchArray(SQLITE3_ASSOC)) {

                                $cleLimit = $requetePosCle["count(id)"];
                            }



                            // Donne l'emplacement actuel
                            $emplacement='SELECT couloir.id,couloir.type FROM couloir WHERE couloir.id =:empl'; // requete qui donne les information sur la position actuelle du joueur
                            $requete = $sqlite -> prepare($emplacement);	
	                        $requete -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                        $result = $requete -> execute();
                        
                            while($requete = $result -> fetchArray(SQLITE3_ASSOC)) {
                            
                                if ($requete['type'] == "cle"){
                               
                                    if (isset($_SESSION['nbCle']) AND $_SESSION['nbCle'] < $cleLimit){

                                        $_SESSION['nbCle'] = 1;
                                    }
                                    else
                                    {
                                        $_SESSION['nbCle'] = 0;
                                    }
                                }

                                echo "<h1>Vous etes emplacement : " .$requete['id']."(type : " .$requete['type']."), cle :".$_SESSION['nbCle']." </h1>";
                            }
                        
                            echo "<h1> Vous pouvez allez dans ces direction : </h1>\n";


                            // Recuperer les direction possible si  empl = couloir2 et permettre le deplacement
                            $position1='SELECT couloir1, couloir2, position1, position2, type FROM passage WHERE passage.couloir2=:empl'; // requete pour voir la position liés l'emplacement actuelle
                            $requetePos1 = $sqlite -> prepare($position1);	
	                        $requetePos1 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                        $resultPos1 = $requetePos1 -> execute();


                            while($requetePos1 = $resultPos1 -> fetchArray(SQLITE3_ASSOC)) {

                            switch($requetePos1['position1']){
                                    case "O" :
                                        $dir = "Gauche";
                                        break;
                                    case "N" :
                                        $dir = "Haut";
                                        break;
                                    case "S" :
                                        $dir = "Bas";
                                        break;
                                    case "E" :
                                        $dir = "Droite";
                                        break;
                                    case "C" :
                                        $dir = "Passage Secret";
                                        break;
                                }
              
                                if ($requetePos1["type"] != "grille"){

                                    echo "<h1> - <a href ='index.php?couloir=".$requetePos1['couloir1']."'>".$dir." (type : ".$requetePos1['type'].")</a> </h1>";    
                                }
                                else if ($requetePos1["type"] == "grille" AND $_SESSION['nbCle'] == 0 ){
                                    echo "<h1> - ".$dir." (type :".$requetePos1['type'].") : Fermer </h1>";
                                }

                                else {
                                    echo "<h1> - <a href ='index.php?couloir=".$requetePos1['couloir1']."'>".$dir." (type : ".$requetePos1['type'].")</a> </h1>";
                                    $_SESSION['nbCle'] --; 
                                }
                            }     
          

                            // Recuperer les direction possible si  empl = couloir1 et permettre le deplacement
                            $position2='SELECT couloir1, couloir2, position1, position2, type FROM passage WHERE passage.couloir1=:empl'; // requete pour voir la position liés l'emplacement actuelle
                            $requetePos2 = $sqlite -> prepare($position2);	
	                        $requetePos2 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                        $resultPos2 = $requetePos2 -> execute();

                            while($requetePos2 = $resultPos2 -> fetchArray(SQLITE3_ASSOC)) {

                                switch($requetePos2['position2']){
                                    case "O" :
                                        $dir = "Gauche";
                                        break;
                                    case "N" :
                                        $dir = "Haut";
                                        break;
                                    case "S" :
                                        $dir = "Bas";
                                        break;
                                    case "E" :
                                        $dir = "Droite";
                                        break;
                                    case "C" :
                                        $dir = "Passage Secret";
                                        break;
                                }

                                if ($requetePos2["type"] != "grille"){

                                    echo "<h1> - <a href ='index.php?couloir=".$requetePos2['couloir2']."'>".$dir." (type : ".$requetePos2['type'].")</a> </h1>";
                                }
                                else if ($requetePos2["type"] == "grille" AND $_SESSION['nbCle'] == 0 ){
                                    echo "<h1> - ".$dir." (type :".$requetePos2['type'].")  : Fermer  </h1>";
                                }

                                else {
                                    echo "<h1> - <a href ='index.php?couloir=".$requetePos2['couloir2']."'>".$dir." (type : ".$requetePos2['type'].")</a> </h1>";
                                    $_SESSION['nbCle'] --; 
                                }
                            }
                        }
                    }
                }
            }
         // Fermer Base de Données
         $sqlite -> close();
         
        ?>



        </body>

    </html>

