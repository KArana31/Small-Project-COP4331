const urlBase = 'http://www.4331COP.com/LAMPAPI';
const extension = 'php';

let userId = 0;
let firstName = "";
let lastName = "";


function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";

	let login = document.getElementById("loginName").value;
	let password = document.getElementById("loginPassword").value;
//	var hash = md5( password );

	document.getElementById("loginResult").innerHTML = "";

	let tmp = {login:login,password:password};
//	var tmp = {login:login,password:hash};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/LoginAPI.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;

				if( userId < 1 )
				{
					document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
					return;
				}

				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;

				saveCookie();

				window.location.href = "color.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}

}

function saveCookie()
{
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	let data = document.cookie;
	let splits = data.split(",");
	for(var i = 0; i < splits.length; i++)
	{
		let thisOne = splits[i].trim();
		let tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}

	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

function createUser()
{
    firstName = "";
    lastName = "";

    let fName = document.getElementById("firstName").value;
    let lName = document.getElementById("lastName").value;
    let login = document.getElementById("regUsername").value;
    let password = document.getElementById("regPassword").value;
    document.getElementById("registerResult").innerHTML = "";

    var tmp = {firstName:fName,lastName:lName,login:login,password:password};
//  var tmp = {login:login,password:hash};
    let jsonPayload = JSON.stringify(tmp);

    let url = urlBase + '/Register.' + extension;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try
    {
        xhr.onreadystatechange = function()
        {
            if (this.readyState == 4 && this.status == 200)
            {
                document.getElementById("registerResult").innerHTML = "User has been created.";
				backToLogin();
            }
            else
			{
				document.getElementById("registerResult").innerHTML = "Invalid Username or Password.";
			}
        };
        xhr.send(jsonPayload);
    }


    catch (err)
    {
        document.getElementById("registerResult").innerHTML = err.message;
    }
}

function goRegister()
{
	window.location.href = "register.html";
	// let url = urlBase + '/Register.' + extension;
}

function backToLogin()
{
	window.location.href = "index.html";
	// let url = urlBase + '/Index.' + extension;
}

function backToHome()
{
	window.location.href = "color.html";
	// let url = urlBase + '/Index.' + extension;
}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function searchContact()
{
	let srch = document.getElementById("search").value;
	document.getElementById("searchResults").innerHTML = "";

	// let contactList = "";

	let tmp = {search:srch,userId:userId};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/SearchContacts.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("searchResults").innerHTML = "\n\nSearch Results:";
				let jsonObject = JSON.parse( xhr.responseText );
				var table = document.getElementById("contactList");
				for( let i=0; i<jsonObject.results.length; i++ )
				{
					var row = table.insertRow();
				  var first = row.insertCell(0);
				  var last = row.insertCell(1);
				  var phone = row.insertCell(2);
				  var email = row.insertCell(3);
				  var address = row.insertCell(4);
					var cellInstruction = row.insertCell(5);
					    cellInstruction.innerHTML = '	<button type="button" id="editButton" class="buttons2" onclick="doLogout();"> Edit </button>'
					var cellInstruction = row.insertCell(6);
					    cellInstruction.innerHTML = cellInstruction.innerHTML = '	<button type="button" id="deleteButton" class="buttons2" onclick="doLogout();"> Delete </button>'
					first.innerHTML = jsonObject.results[i].FirstName;
					last.innerHTML = jsonObject.results[i].LastName;
					phone.innerHTML = jsonObject.results[i].Phone;
					email.innerHTML = jsonObject.results[i].Email;
					address.innerHTML = jsonObject.results[i].Address;
					// contactList += jsonObject.results[i].FirstName + " " + jsonObject.results[i].LastName + " " + jsonObject.results[i].Phone + " " + jsonObject.results[i].Email + " " + jsonObject.results[i].Address;
					if( i < jsonObject.results.length - 1 )
					{
						contactList += "<br />\r\n";
					}
				}

				document.getElementsByTagName("p")[0].innerHTML = contactList;
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("searchResults").innerHTML = err.message;
	}
}

function addContact()
{
    let frName = document.getElementById("firstName").value;
    let laName = document.getElementById("lastName").value;
    let phone = document.getElementById("phoneNumber").value;
    let email = document.getElementById("email").value;
    let address = document.getElementById("address").value;
    document.getElementById("AddContactsResult").innerHTML = "";

    let tmp = {firstName:frName,lastName:laName,phoneNumber:phone,email:email,address:address,userId:userId};
//  var tmp = {login:login,password:hash};
    let jsonPayload = JSON.stringify(tmp);

    let url = urlBase + '/AddContacts.' + extension;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try
    {
        xhr.onreadystatechange = function()
        {
            if (this.readyState == 4 && this.status == 200)
            {
                document.getElementById("AddContactsResult").innerHTML = "Contact has been added.";
								saveCookie();
				//backToHome();
			}
        };
        xhr.send(jsonPayload);
    }

    catch (err)
    {
        document.getElementById("AddContactsResult").innerHTML = err.message;
    }
}

function editContact()
{
	// let newFrName = document.getElementById("editFirstName").value;
	// let newLaName = document.getElementById("editLastName").value;
	// let newPhone = document.getElementById("editPhoneNumber").value;
	// let newEmail = document.getElementById("editEmail").value;
	// let newAddress = document.getElementById("editAddress").value;
	//
	// let tmp = {firstName:newFrName,lastName:newLaName,phoneNumber:newPhone,email:newEmail,address:newAddress,userId:userId};
	// let jsonPayload = JSON.stringify( tmp );
	//
	// let url = urlBase + '/SearchContacts.' + extension;
	//
	// let xhr = new XMLHttpRequest();
	// xhr.open("POST", url, true);
	// xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
}

function deleteContact()
{

}

function goAddContact()
{
	readCookie();
	window.location.href = "addcontact.html";
	// let url = urlBase + '/Register.' + extension;
}
