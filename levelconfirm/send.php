<?
require "../include/connect.php";
$User= $_POST['UC'];
$Answer= $_POST['Answer'];
$Level= $_POST['Level'];
$sql= 'UPDATE requests SET Answer = "'.$Answer.'" WHERE UserCode='.$User.' AND Answer= "Not answered"' ;
$conn->query($sql);
if ($Answer=="Yes")
{
	$sql= 'UPDATE users SET Level = "'.$Level.'" WHERE UserCode='.$User.'' ;
	$conn->query($sql);
}
$conn->close();
?>