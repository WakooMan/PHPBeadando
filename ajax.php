<?php
session_start();
$_SESSION[$_GET['nev']]=
[
    'tol'=>$_GET['tol'],
    'ig'=>$_GET['ig'],
];
$interval = $_SESSION[$_GET['nev']];
$series = $_SESSION[$_GET['sorozatok']];
$message = "";
if($interval['tol']>5 && $interval['ig'] > 5)
{
    $message =$message.'<td><button type="submit" value="'.($interval['tol']-5).'-'.($interval['ig']-($interval['ig']%5)).'">'.($interval['tol']-5).'-'.($interval['ig']-($interval['ig']%5)).'</button></td>';
}
$message= $message.'<td><button type="submit" value="'.($interval['tol']).'-'.($interval['ig']).'" disabled>'.($interval['tol']).'-'.((count($series)<$interval['ig'])?count($series):$interval['ig']).'</button></td>';
if(count($series)>=($interval['tol']+5))
{
    $tmpig = (count($series)<($interval['ig']+5))?count($series):$interval['ig']+5;
    $message=$message.'<td><button type="submit" value="'.($interval['tol']+5).'-'.$tmpig.'">'.($interval['tol']+5).'-'.$tmpig.'</button></td>';
}
print($message);
?>