<?php

    // retrieves results from the getRequestInfo function
    // stores the data from json file in an array
    $inData = getRequestInfo();

    
    $firstName  = $inData["firstName"];
    $lastName = $inData["lastName"];
    $login = $inData["login"];
    $password = $inData["password"];

    // open a new connection to the MySQL server
    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
    
    if ($conn->connect_error) 
    {
        returnWithError( $conn->connect_error );
    }
    else
    {
        # Get all IDs of the users with the given login.  
        $getId = $conn->prepare("SELECT ID FROM Users WHERE Login=?");
        $getId->bind_param("s", $inData["login"]);
        $getId->execute();
        $result = $getId->get_result();

        # check if there is already a user with that login, return error
        if ($row = $result->fetch_assoc())
        {
            returnWithError("login in use");
        }
        else
        {
            #Create a user 
            $stmt = $conn->prepare("INSERT into Users (firstName,lastName,login,password) VALUES(?,?,?,?)");
            $stmt->bind_param("ssss", $firstName, $lastName, $login, $password);
            $stmt->execute();
            $stmt->close();

            #Get ID of the new user
            $stmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
            $stmt->bind_param("ss", $inData["login"], $inData["password"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $stmt->close();
            $conn->close();
            
        
            returnWithInfo( $row['firstName'], $row['lastName'], $row['ID'] );
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

    function returnWithInfo( $firstName, $lastName, $id )
    {
        $retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
        sendResultInfoAsJson( $retValue );
    }
    
?>
