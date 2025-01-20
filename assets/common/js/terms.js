$(document).ready(function () {
  let cookie = document.cookie
    .split(";")
    .find((c) => c.includes("terms_accepted"));
  // console.log(cookie);

  if (!cookie) {
    document.getElementById("termsModal").classList.remove("hideItem");
    document.querySelector(".modal-backdrop").classList.remove("hideItem");
  }
  if (cookie) {
    document.getElementById("termsModal").classList.add("hideItem");
    document.querySelector(".modal-backdrop").classList.add("hideItem");
    document.querySelector(".modal-terms-container").classList.add("hideItem");


  }

  $(".btn_decline").click(function () {
    window.location.href = "https://www.google.com";
  });

  $(".btn_accept_terms").click(function () {
    if (!$(".check_accept").is(":checked")) {
      $(".accept_container").css({
        border: "1px solid red",
        padding: "5px",
        "border-radius": "5px",
      });
      return;
    }
    setCookie("terms_accepted", "sobunny", 24);
    document.getElementById("termsModal").remove();
    document.querySelector(".modal-backdrop").remove();
  });
});
function setCookie(name, value, hours) {
  var expires = "";
  if (hours) {
    var date = new Date();
    date.setTime(date.getTime() + hours * 60 * 60 * 1000);
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
