<?php

	$inData = getRequestInfo();
	
	$id = 0;
	$firstName = "";
	$lastName = "";

	// open a new connection to the MySQL server
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 

	// check if new connection is valid
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		//  if the connection is valid 
		// prepare an SQL statement for execution and then bind variables to the statement as parameters
		$stmt = $conn->prepare("SELECT ID,DateCreated,DateLastLoggedIn,FirstName,LastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $inData["login"], $inData["password"]);
		
		// execute SQL statement and get the result
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{
			// return infor if login info found
			returnWithInfo( $row['firstName'], $row['lastName'], $row['ID'] );
		}
		else
		{
			// return error if login info not found
			returnWithError("No Records Found");
		}

		// close database connection
		$stmt->close();
		$conn->close();
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
		echo $obj;	// print message
	}
	
	function returnWithError( $err )
	{
		// stores connection error in string 
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $firstName, $lastName, $id )
	{
		// stores search result in string
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>
