<!DOCTYPE html>
<html>
<?php require 'head.html';?>

<body class="bg-gif">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Accedi al mio sito!</div>
      <div class="card-body">
        <form method="post" onsubmit="return sendPost(this, 'login.php')">
          <div class="form-group">
            <label for="username">Username</label>
            <input id="username" class="form-control" type="text" name="username" placeholder="Username" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" id="password" type="password" name="password" placeholder="Password" required>
            <div class="invalid-feedback">Username o password errati.</div>
          </div>
          <button class="btn btn-primary btn-block">Accedi</button>
        </form>
        <div class="text-center">
          <a class="small mt-3" href="sign-up-front.php">Registra un account</a>
        </div>
      </div>
    </div>
  </div>
<body>


  <script>  
  function sendPost(b, loc, addData=null, callback=null) {
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
                    if(res.not_empty){
                      window.location.href = "dashboard.php";
                    }else{
                      $("input").addClass("is-invalid");
                    }
                    return false;
                }
            }
        });
        return false; 
}
</script>
</html>
