<?php?

	$inData = getRequestInfo();
	$ID = $inData["ID"];
	$userId = $inData["userId"];
	
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		try{
			$stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ? AND UserID = ?");
			$stmt->bind_param("ss", $inData["ID"], $inData["userId"]);
			
			
			
			$stmt->execute();
			$stmt->close();
			$conn->close();
			returnWithError("");
		
		}
		catch(Exception $ex){
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
>
