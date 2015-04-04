function checkForm()
{
//fetching values from all input fields and storing them in variables
    var pname = document.getElementById("pname1").value;
    var url = document.getElementById("url1").value;
    var descr = document.getElementById("descr1").value;
    var goal = document.getElementById("goal1").value;
    var deadline = document.getElementById("deadline1").value;

//Check input Fields Should not be blanks.
    if (pname == '' || url == '' || descr == '' || goal == '' || deadline == '')
    {
        alert("Fill All Fields");
    }

    else
    {

        //Notifying error fields
        var pname1 = document.getElementById("pname");
    var url1 = document.getElementById("url");
    var descr1 = document.getElementById("descr");
    var goal1 = document.getElementById("goal");
    var deadline1 = document.getElementById("deadline");

        //Check All Values/Informations Filled by User are Valid Or Not.If All Fields Are invalid Then Generate alert.
                //Submit Form When All values are valid.
            document.getElementById("myForm").submit();
    }
}

//AJAX Code to check  input field values when onblur event triggerd.
function validate(field, query)
{
        var xmlhttp;

if (window.XMLHttpRequest)
  {// for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState != 4 && xmlhttp.status == 200)
        {
            document.getElementById(field).innerHTML = "Validating..";
        }
        else if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            document.getElementById(field).innerHTML = xmlhttp.responseText;
        }
        else
        {
            document.getElementById(field).innerHTML = "Error Occurred. <a href='index.php'>Reload Or Try Again</a> the page.";
        }
    }
    xmlhttp.open("GET", "validation.php?field=" + field + "&query=" + query, false);
    xmlhttp.send();
}
