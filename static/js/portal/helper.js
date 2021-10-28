/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function myFunction() {
  var x = document.getElementById("contrasena");
  var y = document.getElementById("icon");
  if (x.type === "password") {
    x.type = "text";
    y.className ="fa fa-eye";
  } else {
    x.type = "password";
    y.className ="fa fa-eye-slash";
  }
}

function myFunctionVer(id) {
  var x = document.getElementById("contrasena"+id);
  var y = document.getElementById("icon"+id);
  if (x.type === "password") {
    x.type = "text";
    y.className ="fa fa-eye";
  } else {
    x.type = "password";
    y.className ="fa fa-eye-slash";
  }
}

 function goBack() {
        window.history.back();
    }