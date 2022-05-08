<!DOCTYPE html>
<html>

<?php require 'head.html'?>

<body class="bg-gif">
  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Registrati al mio sito</div>
      <div class="card-body">
        <form id="sign-up-form" method="post" action="sign-up.php" onsubmit="return sendPost(this,'sign-up.php')">
          <div class="form-group">
            <label for="username">Username</label>
            <input class="form-control" name="username" id="username" type="text" placeholder="Inserisci un username" required>
            <div class="invalid-feedback">Username already exist!</div>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-6">
                <label for="name">Nome</label>
                <input class="form-control" name="name" id="name" type="text" placeholder="Inserisci il tuo nome" required>
              </div>
              <div class="col-md-6">
                <label for="surname">Cognome</label>
                <input class="form-control" name="surname" id="surname" type="text" placeholder="Inserisci il tuo cognome" required>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="email">Indirizzo email</label>
            <input class="form-control" name="email" id="email" type="email" placeholder="Inserisci la tua email" required>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-6">
                <label for="password">Password</label>
                <input name="password" class="form-control" id="password" type="password" onkeyup='checkPass();' placeholder="Password" required>
              </div>
              <div class="col-md-6">
                <label for="check_password">Conferma password <span id='message'></span></label>
                <input name="check_password" class="form-control" id="check_password" type="password" onkeyup='checkPass();' placeholder="Password" required>
              </div>
              <div class="col-md-6">
                <label for="date">Data di nascita</label>
                <input class="form-control" type="date" name="date" id="date" required>
              </div>
            </div>
          </div>
          <button class="btn btn-primary btn-block">Registrati</button>
        </form>
        <div class="text-center">
          <a class="small mt-3" href="home.php">Pagina di login</a>
        </div>
      </div>
    </div>
  </div>


<script>
function checkUsername() {
  username = $("#username").val();
  res = false;
  $.ajax({
    url: "check_user.php?u=" + username,
    type: 'GET',
    async: false,
    cache: false,
    timeout: 30000,
    error: function() {
      return false;
    },
    success: function(resp) {
    //   console.log(resp);
      if (resp == "false") {
        res = true;
      } else {
        $("#username").addClass("is-invalid");
        res = false;
      }
    }
  });
  return res;
}

function checkPass() {
  if (document.getElementById('password').value == document.getElementById('check_password').value) {
    document.getElementById('message').style.color = 'green';
    document.getElementById('message').innerHTML = 'Ok!';
    return true;
  } else {
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').innerHTML = 'Ritenta';
    return false;
  }
}


function sendPost(b, loc, addData=null, callback=null) {
        if(!checkUsername()) {
          return false;
        }
        if(!checkPass()) {
          return false;
        }
        sdata=$(b).serializeArray();
        if(addData!=null){
        sdata.push(addData);}
        $.ajax({
            url: loc,
            type: 'POST',
            data: sdata,
            async: true,
            cache: false,
            timeout: 30000,
            dataType: "json",
            error: function () {
                return false;
            },
            success: function (res) {
                console.log(res);
                //res = JSON.parse(resp);
                if (res.res == false) {
                    return false;
                } else if (res.res == true) {
                    responseMessage();
                    return false;
                }
            }
        });
        return false; 
}

function responseMessage(){
    console.log("Partita");
    $(".card-body").html("");
    div=$(".card-body").append("<div class='d-flex justify-content-center'></div>")
    div.append("<h3 class='text-center text-success'><i class='fa fa-check-circle'></i> Registrazione avvenuta con successo!</h3>")
    div.append("<p class='text-center'>Accedi con le tue credenziali! </p>")
    div.append('<div class="text-center"><a class="small mt-3" href="home.php">Pagina di login</a></div>')
}
</script>
</body>

</html>


