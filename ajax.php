<?php
session_start();
include('storages.php');
$users = new UsersStorage();
$user = (isset($_SESSION['felhasznalo']))?$users->findById($_SESSION['felhasznalo']):NULL;
$_SESSION[$_GET['nev']]=
[
    'tol'=>$_GET['tol'],
    'ig'=>$_GET['ig'],
];
$interval = $_SESSION[$_GET['nev']];
$series = $_SESSION[$_GET['sorozatok']];
$tablecells = '';
$buttons = '';
$tablecells= $tablecells.'<tr><th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th></tr>';
foreach(array_reverse(array_slice($series,-$interval['ig'],$interval['ig']-$interval['tol']+1)) as $ser)
{        
    $tablecells=$tablecells.'<tr><td>'.$ser['title'].'</td> <td>'.count($ser['episodes']).'</td> <td>'.end($ser['episodes'])['date'].'</td> <td><a href="reszletek.php?id='.$ser['id'].'">Részletek</a></td> '.(($user!==NULL && $user['isadmin'])?'<td><a href="modifySeries.php?id='.$ser['id'].'">Módosítás</a></td> <td><a href="deleteSeries.php?id='.$ser['id'].'">Törlés</a></td>':'').'</tr>';
}
if($interval['tol']>5 && $interval['ig'] > 5)
{
    $buttons =$buttons.'<td><button type="submit" value="'.($interval['tol']-5).'-'.($interval['ig']-($interval['ig']%5)).'">'.($interval['tol']-5).'-'.($interval['ig']-($interval['ig']%5)).'</button></td>';
}
$buttons= $buttons.'<td><button type="submit" value="'.($interval['tol']).'-'.($interval['ig']).'" disabled>'.($interval['tol']).'-'.((count($series)<$interval['ig'])?count($series):$interval['ig']).'</button></td>';
if(count($series)>=($interval['tol']+5))
{
    $tmpig = (count($series)<($interval['ig']+5))?count($series):$interval['ig']+5;
    $buttons=$buttons.'<td><button type="submit" value="'.($interval['tol']+5).'-'.$tmpig.'">'.($interval['tol']+5).'-'.$tmpig.'</button></td>';
}
print($tablecells.'|'.$buttons);
?>