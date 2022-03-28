$(document).ready(function()
  {
    $(document).on("click","#addCarrinho", function()
      {
        var qt = 1;
        var nm = document.querySelector("#nm").innerText;
        var price = document.querySelector("#price").innerText;
        $.ajax({
            method:"POST",
            data:{qt,nm,price},
            url:'https://localhost/stringmusic/mercadopag/controllers/CarrinhoController.php',
            success: function(retorno)
            {
                window.location.href="https://localhost/stringmusic/mercadopag/view/mercadopag.php";
            }
        });
      });
    });