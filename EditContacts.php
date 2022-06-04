<?php

	$inData = getRequestInfo();
	
	$firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
	$phone = $inData["phoneNumber"];
	$email = $inData["email"];
	$address = $inData["address"];
	
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		try{
			$stmt = $conn->prepare("Update Contacts SET FirstName = ?, LastName = ?, Phone = ?, Email = ?, Address = ? WHERE ID = ? AND UserID = ?");
			$stmt->bind_param("sssssss", $firstName, $lastName, $phone, $email, $address, $inData["ID"], $inData["UserID"]);
			
			$stmt->execute();
			$stmt->close();
			$conn->close();
			
			returnWithError("");
		}
		catch(Exception $ex)
		{
			returnWithError( $ex->getMessage );
		}	
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}


?>