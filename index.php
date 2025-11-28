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
            // Ouvrir Base de Données
      
            $bdd_fichier = 'labyrinthe.db';	
	        $type = "depart";
            $typeSortie = "sortie";
	        $sqlite = new SQLite3($bdd_fichier);
            $nbCle = 0;
            $empl = $_GET['couloir'];
            $cle = "cle";
            //$_GET['couloir'] == 0;
            
            
            



            // Attribuer position de depart initial
            $initial='SELECT couloir.id, couloir.type FROM couloir WHERE type=:type'; //requete emplacement initial
            $requeteINIT = $sqlite -> prepare($initial);	
	        $requeteINIT -> bindValue(':type', $type, SQLITE3_TEXT);
	        $resultINIT = $requeteINIT -> execute();





            // Recuperer la valeur de l'id de la page
            while($requeteINIT = $resultINIT -> fetchArray(SQLITE3_ASSOC)) {
                    
                  $init = $requeteINIT['id']; 
                  
            }
                
            if ($empl == 0) {
                include("pages/page0.php");
                echo "<h1>Cliquez  <a href ='index.php?couloir=".$init."'> ici </a> pour jouer </h1>";
            }
            else {    

                if (isset($empl) == $init)
                {
             
            
                //Verifier si le type = sortie et change de page dans ce cas
                $verifPartie='SELECT couloir.id,couloir.type FROM couloir WHERE couloir.type =:typeSortie';
                $requeteVerif = $sqlite -> prepare($verifPartie);	
	            $requeteVerif -> bindValue(':typeSortie', $typeSortie, SQLITE3_TEXT);
	            $resultVerif = $requeteVerif -> execute();

                while($requeteVerif = $resultVerif -> fetchArray(SQLITE3_ASSOC)) {

                    if ($empl == $requeteVerif['id']){

                        include("pages/pageFin.php");
                        echo "<h1><a href ='index.php?couloir=13'>  Recommencer</a></h1>";
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
                        $emplacement='SELECT couloir.id,couloir.type FROM couloir WHERE couloir.id =:empl';
                        $requete = $sqlite -> prepare($emplacement);	
	                    $requete -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                    $result = $requete -> execute();
                        
                        while($requete = $result -> fetchArray(SQLITE3_ASSOC)) {
                            
                          
                        if ($requete['type'] == "cle"){
                               
                                if (isset($_SESSION['nbCle']) AND $_SESSION['nbCle'] < $cleLimit){

                                    $_SESSION['nbCle'] ++;
                                    //$idCle = $requete['id'];
                                }

                                else
                                {
                                    $_SESSION['nbCle'] = 0;
                                }
                                
                                
                            }

                            echo "<h1>Vous etes emplacement : " .$requete['id']."(type : " .$requete['type']."), cle :".$_SESSION['nbCle']." </h1>";
                            
                        }
                        


                        echo "<h1> Vous pouvez allez dans ces direction : </h1>\n";


                        // Recuperer les direction possible si couloir2 = id et permettre le deplacement
                        $position1='SELECT couloir1, couloir2, position1, position2, type FROM passage WHERE passage.couloir2=:empl'; // requete pour voir la position liés l'emplacement actuelle
                        $requetePos1 = $sqlite -> prepare($position1);	
	                    $requetePos1 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                    $resultPos1 = $requetePos1 -> execute();


                        while($requetePos1 = $resultPos1 -> fetchArray(SQLITE3_ASSOC)) {
              
                            if ($requetePos1["type"] != "grille"){

                                echo "<h1> - <a href ='index.php?couloir=".$requetePos1['couloir1']."'>".$requetePos1['position1']." type (".$requetePos1['type'].")</a> </h1>";    
                            }
                            else if ($requetePos1["type"] == "grille" AND $_SESSION['nbCle'] == 0 ){
                                echo "<h1> - ".$requetePos1['position1']." type (".$requetePos1['type'].") Vous ne pouvez pas passer sans cle  </h1>";
                            }

                            else {
                                echo "<h1> - <a href ='index.php?couloir=".$requetePos1['couloir1']."'>".$requetePos1['position1']." type (".$requetePos1['type'].")</a> </h1>";
                                $_SESSION['nbCle'] --; 
                            }
                        }     
          

                        // Recuperer les direction possible si couloir1 = id et permettre le deplacement
                        $position2='SELECT couloir1, couloir2, position1, position2, type FROM passage WHERE passage.couloir1=:empl'; // requete pour voir la position liés l'emplacement actuelle
                        $requetePos2 = $sqlite -> prepare($position2);	
	                    $requetePos2 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                    $resultPos2 = $requetePos2 -> execute();

                        while($requetePos2 = $resultPos2 -> fetchArray(SQLITE3_ASSOC)) {

                            if ($requetePos2["type"] != "grille"){

                                echo "<h1> - <a href ='index.php?couloir=".$requetePos2['couloir2']."'>".$requetePos2['position2']." type (".$requetePos2['type'].")</a> </h1>";
                            }
                            else if ($requetePos2["type"] == "grille" AND $_SESSION['nbCle'] == 0 ){
                                echo "<h1> - ".$requetePos2['position2']." type (".$requetePos2['type'].") Vous ne pouvez pas passer sans cle  </h1>";
                            }

                            else {
                                echo "<h1> - <a href ='index.php?couloir=".$requetePos2['couloir2']."'>".$requetePos2['position2']." type (".$requetePos2['type'].")</a> </h1>";
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

