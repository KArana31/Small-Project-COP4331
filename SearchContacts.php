<?php

	// retrieves results from the getRequestInfo function
	// stores the data from json file in an array
	$inData = getRequestInfo();
	
	// initialize search results and search count
	$searchResults = "";
	$searchCount = 0;
	
	// open a new connection to the MySQL server
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

	// check if new connection is valid
	if ($conn->connect_error) 
	{
		// returns connection error
		returnWithError( $conn->connect_error );
	}
	else
	{
		// if the connection is valid 
		// prepare an SQL statement for execution and then bind variables to the statement as parameters
		$stmt = $conn->prepare("select Name from Contacts where Name like ? and UserID=?");
		$name = "%" . $inData["search"] . "%";
		$stmt->bind_param("ss", $name, $inData["userId"]);
		$stmt->execute();	// execute SQL statement
		
		// gets the result of the prepared SQL statement
		$result = $stmt->get_result()
		
		// loops through each row in the array
		// fetch_assoc() fetches the next row of a result set as an associative array
		while($row = $result->fetch_assoc())
		{
			// if search count is greater than zero append , to results
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;	// increase search count
			
			// append contact name to result
			$searchResults .= '"' . $row["Name"] . '"'. $row["Phone"] .'"' . $row["Email"] . '"'. $row["Email"] . '"'. $row["Email"] 
						. '"'. $row["Country"] . '"'. $row["StateProvince"] . '"'. $row["City"] . '"'. $row["Address"] . '"'. $row["UserID"] . '"';
		}
	
			if( $searchCount == 0 )
		{
			// no items match search criteria
			returnWithError( "No Records Found" );
		}
		else
		{
			// return search results
			returnWithInfo( $searchResults );
		}
		
		// close SQL statement and close connection
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
		// sends http header
		header('Content-type: application/json');
		echo $obj;	// print message
	}
	
	function returnWithError( $err )
	{
		// stores connection error in string 
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $searchResults )
	{
		// stores search results in string 
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>