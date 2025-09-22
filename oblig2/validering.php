<?php 
function validerklassekode($klassekode)
{

 $lovligklassekode=true;
 if (!$klassekode) 
 {
 $lovligklassekode=false;
 }
 else if (strlen($klassekode)!=3) 
 {
 $lovligklassekode=false;
 }
 else
 {
 for ($teller=1;$teller<=3;$teller++)
 {
 $tegn[$teller]=substr($klassekode,$teller-1,1);
 }
 if ($tegn[1] < "A" || $tegn[1] > "Z" || $tegn[2] < "A" || $tegn[2] > "Z")

 {
 $lovligklassekode=false;
 }
 else if ($tegn[3] < "0" || $tegn[3] > "9") 
 {
 $lovligklassekode=false;
 }
 }
 return $lovligklassekode;
}

?> 