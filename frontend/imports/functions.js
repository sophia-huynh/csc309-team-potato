/*
tryLogIn(email, password)
    Given an email and password, checks if they're nonempty. If they are,
    make an AJAX call to trylogin.php and set any errors. On success, reloads
    the page.
*/
function tryLogIn(email, password){
    var error = 0;
    if (email == ""){
        $('#email').addClass("error");
        $('#email').attr('placeholder', 'Email required');
        error = 1;
    }
    if (password == ""){
        $('#password').addClass("error");
        $('#password').attr('placeholder', 'Password required');
        error = 1;
    }
    if (!error){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var error = parseInt(xmlhttp.responseText);
                if (error < 0){
                    if (error != -2){
                        $('#email').addClass("error");
                        $('#email').val("");
                        if (error == -10)
                            $('#email').attr('placeholder', 'Invalid email');
                        else
                            $('#email').attr('placeholder', 'Nonexistent email');
                    }else{
                        $('#password').addClass("error");
                        $('#password').attr('placeholder', 'Invalid password');
                    }
                    $('#password').val("");
                }else{
                    // set cookies to uid
                    location.reload();
                }
            }
        }
        xmlhttp.open("GET", "trylogin.php?email="+email+"&password="+password, true);
        xmlhttp.send();
    }
}

/*
tryRegister(email, username, password)
    Given an email, name and password, checks if they're nonempty. If they are,
    make an AJAX call to tryregister.php and set any errors.
*/
function tryRegister(email, username, password){
    var error = 0;
    if (email == ""){
        $('#email').addClass("error");
        $('#email').attr('placeholder', 'Email required');
        error = 1;
    }
    if (password == ""){
        $('#password').addClass("error");
        $('#password').attr('placeholder', 'Password required');
        error = 1;
    }
    if (username == ""){
        $('#username').addClass("error");
        $('#username').attr('placeholder', 'Username required');
        error = 1;
    }
    if (!error){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var uid = parseInt(xmlhttp.responseText);
                if (uid < 0){
                    if (uid < -1 && uid > -10){
                        // Name already exists
                        $('#username').addClass("error");
                        $('#username').val("");
                        $('#username').attr('placeholder', 'Username taken');
                    }
                    if (uid != -2){
                        // Email already exists
                        $('#email').addClass("error");
                        $('#email').val("");
                        if (uid == -10)
                            $('#email').attr('placeholder', 'Invalid email');
                        else
                            $('#email').attr('placeholder', 'Email in use');
                    }
                    $('#password').val("");
                }else{
                    // Set cookies to uid
                    location.reload();
                }
            }
        }
        xmlhttp.open("GET", "tryregister.php?email="+email+"&password="+password+"&username="+username, true);
        xmlhttp.send();
    }
}
