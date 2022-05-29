<?php

	// retrieves results from the getRequestInfo function
	// stores the data from json file in an array
	$inData = getRequestInfo();

	$FirstName = $inData["FirstName"];
    $LastName = $inData["LastName"];
	$Login = $inData["Login"];
    $Password = $inData["Password"];

	
	// open a new connection to the MySQL server
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	
	// check if new connection is valid
	if ($conn->connect_error)
	{
		// returns connection error
		returnWithError($conn->connect_error);
	}
	else
	{
		// if the connection is valid 
		// prepare an SQL statement for execution and then bind variables to the statement as parameters
		$stmt = $conn->prepare("INSERT INTO Users (FirstName,LastName,Login,Password) VALUES (?,?,?,?)");
		$stmt->bind_param("ssss", $FirstName, $LastName, $Login, $Password);
		
		// execute and close the SQL statement
		$stmt->execute();

        $stmt->close();
		
		// close the connection and return without error
		$conn->close();
        returnWithError("");

	}

	function getRequestInfo()
	{
		// takes a json object and convert it into a php object
		return json_decode(file_get_contents('php://input'), true);
	}
	
	function sendResultInfoAsJson( $obj )
	{
		// sends a raw http header
		header('Content-type: application/json');
		echo $obj;	// print error message
	}
	
	function returnWithError( $err )
	{
		// stores connection error in string 
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>
