<!DOCTYPE html>		
<html lang= fr>
    <head>
	    <meta charset= "UTF-8">	
	    <title>Liste des couloirs</title>
    </head>


    <body>

        <?php include("include/header.php");
	        include("include/nav.php");



            if (isset($_GET['menu']))
            {

                switch($_GET['menu'])
                {

                    case '1': 
                    include("pages/page1.php");
                    break;
      
                default:
                include("pages/page0.php") ;
                }
            }

        ?>

    </body>


