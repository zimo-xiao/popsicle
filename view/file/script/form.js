function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#selecter_img').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}


$("#file").change(function() {
  readURL(this);
});

$('#selecter_img').load(function() {
  Show('selecter_img');
  Hide('selecter_font');
});

$('.selecter').click(function() {
  $('#file').click();
});

$('#up').one('click', function() {
  document.forms['uploader'].submit();
});

$("#color").change(function() {
  if ($(this).val().length == 6) {
    $('#color_prev').css("background-color", '#' + $(this).val());
    $('#up').css("background-color", '#' + $(this).val());
  }
});