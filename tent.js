// MOSTRAR/ESCONDER NAVEGAÇÃO

$("nav ul").hide();

$(".nav-toggle").click( function() {
  $("nav ul").slideToggle("medium");
});

$("nav ul li a, .brand a").click( function() {
  $("nav ul").hide();
});

// SCROLL SUAVE

$(function() {
  var navHeight = $("nav").outerHeight();
  $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top - navHeight
        }, 1000);
        return false;
      }
    }
  });
});

// NAV STICKY

var viewHeight = $(window).height();
var navigation = $('nav');

$(window).scroll( function() {
  if ( $(window).scrollTop() > (viewHeight - 175) ) { // PODE EDITAR AQUI PRA DECIDIR A ALTURA DA NAV
    navigation.addClass('sticky');
  } else {
    navigation.removeClass('sticky');
  }
});

// FAZ OS SPLASHS DO CONTAINER FICAREM CENTRALIZADOS

function centerSplash() {
  var navHeight = $("nav").outerHeight();
  var splashHeight = $(".splash .container").height();
  var remainingHeight = $(window).height() - splashHeight - navHeight;
  $(".splash .container").css({"padding-top" : remainingHeight/2, "padding-bottom" : remainingHeight/2});
}

$( document ).ready( function() {
  centerSplash();
});

$( window ).resize( function() {
  centerSplash();
});