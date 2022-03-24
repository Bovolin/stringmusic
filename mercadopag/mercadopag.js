(function(win,doc) {
    "use strict";
    //Chave publica
    window.Mercadopago.setPublishableKey("TEST-38d06cad-cf26-4269-bceb-94aede7eb3c1");
    //Tipo de Doc
    window.Mercadopago.getIdentificationTypes();
    //Card Bin
    function cardBin(event){
        let textLength = event.target.value.length;
        if(textLength >= 6){
            let bin = event.target.value.substring(0, 6);
            window.Mercadopago.getPaymentMethod({
                "bin": bin
            }, setPaymentMethodInfo);
            Mercadopago.getInstallments({
                "bin": bin,
                "amount": parseFloat(document.querySelector('#amount').value),
            }, setInstallmentInfo);
        }
    };
    if(doc.querySelector('#cardNumber')){
        let cardNumber = doc.querySelector('#cardNumber');
        cardNumber.addEventListener('keyup', cardBin, false);
    }

    //Pegar cartão e bandeira
    function setPaymentMethodInfo(status, response){
        if(status == 200){
            const paymentMethodElement = doc.querySelector('input[name=paymentMethodId');
            paymentMethodElement.value = response[0].id;
            doc.querySelector('.brand').innerHTML="<img src='" + response[0].thumbnail +"' alt='Bandeira do Cartão'>";
            if(response[0].thumbnail == "http://img.mlstatic.com/org-img/MP3/API/logos/visa.gif"){
                var img = document.getElementById('bandeira');
                img.src = '';
                img.src = '../../imgs/visa.png';
                img.classList.add('selecionada');
            }
            else if(response[0].thumbnail == "http://img.mlstatic.com/org-img/MP3/API/logos/master.gif"){
                var img = document.getElementById('bandeira');
                img.src = '';
                img.src = '../../imgs/mastercard.png';
                img.classList.add('selecionada');
            }
            else if(response[0].thumbnail =="http://img.mlstatic.com/org-img/MP3/API/logos/amex.gif"){
                var img = document.getElementById('bandeira');
                img.src = '';
                img.src = '../../imgs/amex.png';
                img.classList.add('selecionada');
            }
            else if(response[0].thumbnail == "http://img.mlstatic.com/org-img/MP3/API/logos/hipercard.gif"){
                var img = document.getElementById('bandeira');
                img.src = '';
                img.src = '../../imgs/hipercard.png';
                img.classList.add('selecionada');
            }
            else if(response[0].thumbnail == "http://img.mlstatic.com/org-img/MP3/API/logos/elo.gif"){
                var img = document.getElementById('bandeira');
                img.src = '';
                img.src = '../../imgs/elo.png';
                img.classList.add('selecionada');
            }
            else if(response[0].thumbnail == "http://img.mlstatic.com/org-img/MP3/API/logos/diners.gif"){
                var img = document.getElementById('bandeira');
                img.src = '';
                img.src = '../../imgs/dinersclub.png';
                img.classList.add('selecionada');
            }
        }
        else alert(`Payment method info error: ${response}`);
    };

    //Colocar parcelas
    function setInstallmentInfo(status, response){
        console.log(response);
        let label = response[0].payer_costs;
        let installmentsSel = doc.querySelector('#installments');
        installmentsSel.options.length = 0;

        label.map(function(elem, ind, obj){
            let txtOpt = elem.recommended_message;
            let valOpt = elem.installments;
            installmentsSel.options[installmentsSel.options.length] = new Option(txtOpt, valOpt);
        });
    };

    //Gerar Token
    function sendPayment(event){
        event.preventDefault();
        window.Mercadopago.createToken(event.target, sdkResponseHandler);
    }
    function sdkResponseHandler(status, response){
        if(status != 200 && status != 201){
            alert("verify filled data");
       }
       else{
           let form = document.querySelector('#pay');
           let card = document.createElement('input');
           card.setAttribute('name', 'token');
           card.setAttribute('type', 'hidden');
           card.setAttribute('value', response.id);
           form.appendChild(card);
           form.submit();
       }
    }
    if(doc.querySelector('#pay')){
        let formPay = doc.querySelector('#pay');
        formPay.addEventListener('submit', sendPayment, false);
    }
})(window,document);