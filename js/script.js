type="text/javascript"
    const slider = document.querySelector(".slider");
    const nextBtn = document.querySelector(".next-btn");
    const prevBtn = document.querySelector(".prev-btn");
    const slides = document.querySelectorAll(".slide");
    const slideIcons = document.querySelectorAll(".slide-icon");
    const numberOfSlides = slides.length;
    var slideNumber = 0;

    //image slider next button
    nextBtn.addEventListener("click", () => {
      slides.forEach((slide) => {
        slide.classList.remove("active");
      });
      slideIcons.forEach((slideIcon) => {
        slideIcon.classList.remove("active");
      });

      slideNumber++;

      if(slideNumber > (numberOfSlides - 1)){
        slideNumber = 0;
      }

      slides[slideNumber].classList.add("active");
      slideIcons[slideNumber].classList.add("active");
    });

    //image slider previous button
    prevBtn.addEventListener("click", () => {
      slides.forEach((slide) => {
        slide.classList.remove("active");
      });
      slideIcons.forEach((slideIcon) => {
        slideIcon.classList.remove("active");
      });

      slideNumber--;

      if(slideNumber < 0){
        slideNumber = numberOfSlides - 1;
      }

      slides[slideNumber].classList.add("active");
      slideIcons[slideNumber].classList.add("active");
    });

    //image slider autoplay
    var playSlider;

    var repeater = () => {
      playSlider = setInterval(function(){
        slides.forEach((slide) => {
          slide.classList.remove("active");
        });
        slideIcons.forEach((slideIcon) => {
          slideIcon.classList.remove("active");
        });

        slideNumber++;

        if(slideNumber > (numberOfSlides - 1)){
          slideNumber = 0;
        }

        slides[slideNumber].classList.add("active");
        slideIcons[slideNumber].classList.add("active");
      }, 4000);
    }
    repeater();

    //stop the image slider autoplay on mouseover
    slider.addEventListener("mouseover", () => {
      clearInterval(playSlider);
    });

    //start the image slider autoplay again on mouseout
    slider.addEventListener("mouseout", () => {
      repeater();
});


function esgotado(){
  alert("Este produto está esgotado!");
}


function Onlychars(e)
{
	var tecla=new Number();
	if(window.event) {
		tecla = e.keyCode;
	}
	else if(e.which) {
		tecla = e.which;
	}
	else {
		return true;
	}
	if((tecla >= "48") && (tecla <= "57")){
		return false;
	}
}

function Onlynumbers(e)
{
	var tecla=new Number();
	if(window.event) {
		tecla = e.keyCode;
	}
	else if(e.which) {
		tecla = e.which;
	}
	else {
		return true;
	}
	if((tecla >= "1") && (tecla <= "256")){
		return false;
	}
}

function Only(e)
{
	var tecla=new Number();
	if(window.event) {
		tecla = e.keyCode;
	}
	else if(e.which) {
		tecla = e.which;
	}
	else {
		return true;
	}
	if((tecla >= "97") && (tecla <= "122")){
		return false;
	}
}

function formatarMoeda() {
  var elemento = document.getElementById('valor');
  var valor = elemento.value;

  valor = valor + '';
  valor = parseInt(valor.replace(/[\D]+/g, ''));
  valor = valor + '';
  valor = valor.replace(/([0-9]{2})$/g, ",$1");

  if (valor.length > 6) {
      valor = valor.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
  }

  elemento.value = valor;
  if(valor == 'NaN') elemento.value = '';
}

function menuAlterna(){
  const trocaMenu = document.querySelector('.menu');
  trocaMenu.classList.toggle('active');
}

function abreJanela(URL) {
  location.href = URL; // se for popup utiliza o window.open
}

function payment(event){
  const valor = event.value;
  const cartao = document.querySelector("#payment-cartao");
  const boleto = document.querySelector("#payment-boleto");
  const pix = document.querySelector("#payment-pix");
  if(valor == 'payment-cartao'){
    cartao.classList.add('selecionado');
    boleto.classList.remove('selecionado');
    pix.classList.remove('selecionado');
  }
  else if(valor == 'payment-boleto'){
    boleto.classList.add('selecionado');
    cartao.classList.remove('selecionado');
    pix.classList.remove('selecionado')
  }
  else{
    pix.classList.add('selecionado');
    boleto.classList.remove('selecionado');
    cartao.classList.remove('selecionado')
  }
}

function visivel(){
  var star = document.getElementById("div-start-widget")

  if(star.style.display == "none"){
    star.style.display = "block"
  }
  else if(star.style.display == "block"){
    star.style.display = "none"
  }
}

function star(){
  var s5 = document.getElementById("rate-5")
  var s4 = document.getElementById("rate-4")
  var s3 = document.getElementById("rate-3")
  var s2 = document.getElementById("rate-2")
  var s1 = document.getElementById("rate-1")
  var star_input = document.getElementById("input-star")
  if(s5.checked){
    star_input.value = s5.value
  }
  else if(s4.checked){
    star_input.value = s4.value
  }
  else if(s3.checked){
    star_input.value = s3.value
  }
  else if(s2.checked){
    star_input.value = s2.value
  }
  else if(s1.checked){
    star_input.value = s1.value
  }

}