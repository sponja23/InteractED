<?php
function getUnconfirmed(){
	require "../../include/connect.php";
	$sql = 'SELECT CategoryName, CategoryID, CategoryParent FROM Categories WHERE Implemented = 0';
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../../images/categories/" . $row['CategoryID'] . ".*");

            echo   '<tr> 
            			<th>' . $row['CategoryName'] . '</th>
            			<th>' . $row['CategoryParent'] . '</th>
            			<th><a id="' . $row['CategoryID'] . '" class="categoryImage" href=""><img class="circle" src="' . $Image[0] . '" alt="' . $row['CategoryName'] . '" width="80" height="80"></a></th>
            			<th align="center">
                            <a href="approve/?id=' . $row['CategoryID'] . '" class="waves-effect waves-light blue btn"><i class="material-icons left">add_circle</i>Aceptar</a>
                            <a href="decline/?id=' . $row['CategoryID'] . '" class="waves-effect waves-light red btn"><i class="material-icons left">cancel</i>Rechazar</a>
                        </th>
            		<tr>';
        }
    }


}
?>