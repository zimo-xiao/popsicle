function Show(idiv) {
  if (document.getElementById(idiv)) {
    var ui = document.getElementById(idiv);
    ui.style.display = "block";
  }
}

function Hide(idiv) {
  if (document.getElementById(idiv)) {
    var ui = document.getElementById(idiv);
    ui.style.display = "none";
  }
}