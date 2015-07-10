(function () {

  var headerImage = $("#header-image"); // Used < medium
  //var headerBackgroundImage = $("#header-background-image"); // Used < large

  $(window).resize(function () {
    positionHeaderImage();
  });

  positionHeaderImage();

  function positionHeaderImage() {
    var w = jQuery(window).width();
    //var image = headerImage.is(":visible") ? headerImage : headerBackgroundImage;

    headerImage.css({
      position: "relative",
      left: (w - headerImage.width()) / 2
    });
  }

})();
