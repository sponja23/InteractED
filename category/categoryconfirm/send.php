<?
require "../include/connect.php";
$CategoryID= $_POST['CategoryID'];
$Answer= $_POST['Answer'];
$sql= 'UPDATE categories SET Implemented = "'.$Answer.'" WHERE CategoryID ='.$CategoryID.' AND Implemented= 0' ;
$conn->query($sql);
$conn->close();
if ($Answer=="1"){
	echo $CategoryID;
}
if ($Answer=="2"){
	echo "0";
}

?>