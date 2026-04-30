// this function revealete the password


function showPassword(myInput ) {
  var x = document.getElementById(myInput);
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}


