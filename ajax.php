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
$tablecells= $tablecells.'<tr><th>Cím</th><th>Epizódok száma</th><th>Utolsó rész megjelenésének dátuma</th>'.(($user!==NULL && $user['isadmin'])?'<th></th><th></th><th></th>':'').'</tr>';
foreach(array_reverse(array_slice($series,-$interval['ig'],$interval['ig']-$interval['tol']+1)) as $ser)
{        
    $tablecells=$tablecells.'<tr><td>'.$ser['title'].'</td> <td>'.count($ser['episodes']).'</td> <td>'.((count($ser['episodes'])>0)?end($ser['episodes'])['date']:'-').'</td> <td><a href="reszletek.php?id='.$ser['id'].'" class="btn btn-secondary">Részletek</a></td> '.(($user!==NULL && $user['isadmin'])?'<td><a href="modifySeries.php?id='.$ser['id'].'" class="btn btn-secondary">Módosítás</a></td> <td><a href="deleteSeries.php?id='.$ser['id'].'" class="btn btn-secondary">Törlés</a></td>':'').'</tr>';
}
if($interval['tol']>5 && $interval['ig'] > 5)
{
    $buttons =$buttons.'<button class="btn btn-primary" value="'.($interval['tol']-5).'-'.($interval['ig']-($interval['ig']%5)).'">'.($interval['tol']-5).'-'.($interval['ig']-($interval['ig']%5)).'</button>';
}
$buttons= $buttons.'<button class="btn btn-primary" value="'.($interval['tol']).'-'.($interval['ig']).'" disabled>'.($interval['tol']).'-'.((count($series)<$interval['ig'])?count($series):$interval['ig']).'</button>';
if(count($series)>=($interval['tol']+5))
{
    $tmpig = (count($series)<($interval['ig']+5))?count($series):$interval['ig']+5;
    $buttons=$buttons.'<button class="btn btn-primary" value="'.($interval['tol']+5).'-'.$tmpig.'">'.($interval['tol']+5).'-'.$tmpig.'</button>';
}
print($tablecells.'|'.$buttons);
?>