$("#toggle_composer").click(() => {
  $("#composer").toggle();
});

$(".pop_back").click(() => {
  Hide('back')
});

$("#hide_uploader_btn").click(() => {
  Hide('composer')
});

function ajax_get(url, id) {
  $("#" + id).html($.ajax({
    url: url,
    async: false
  }).responseText);
}

lazyload();