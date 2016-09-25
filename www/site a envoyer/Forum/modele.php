<?php
session_start();
$titre="Liste model";
include("includes/identifiants.php");
include('entetenonconnecter.php');
include('menu.php');

include("includes/constants.php");
?>
<?php
//On compte les membres
$pas_de_photogrtaphe=NULL;





       $query=$db->prepare('SELECT id,identifiant,membre_inscrit,je_suis,ville,avatar 
		FROM validation  ');
       
       $query->execute();
       
	   
        ?></br></br></br><div class="fond"><table class="cro">   
        <tr>
        <th class="titre" ><strong>Liste des pseudos des modeles</strong></th>
		<th class="titre"><strong>Avatar</strong></th>		
        <th class="titre"><strong>Habite &agrave;</strong></th>
		
        </tr> 
<?php
		$ie=0;
        //On commence la boucle
		
        while($data=$query->fetch()  )
        {	if($data['je_suis']=="modele")
             {echo'
				
                <tr>
               <td class="titre"><a href="./voirprofile.php?m='.$data['id'].'
                &amp;action=consulter_membre">
                '.stripslashes(htmlspecialchars($data['identifiant'])).'</a></td>
				<td class="titre"><img src="./images/avatars/'.$data['avatar'].'"
       height="70" width="50" alt="Ce membre n a pas d avatar" /></td>
                <td class="titre">'.$data['ville'].'</td>
				
                </tr>
					';
                
				$ie++;
				}
				
		}
		if($ie<1)
			{
				echo'<p>Notre site ne dispose pas pour l\'instant de membre modeles</p>';
			}		 
			  
		else
		{
 ?>       
			</table>
 <?php       echo'Le site compte <strong>'.$ie.'</strong> modeles.<br />';
		}
		$query->CloseCursor();
?>
</div>