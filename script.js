$("#btn").click(function () {
    var formData = new FormData($('#fdados')[0]);
    if (validarForms()) {
        enviarForms(formData);
        // testeGrafico();
    }
});

function enviarForms(formData) {
    $.ajax({
        url: "index.php",//passar a URL
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            let responseData = JSON.parse(response);
            console.log(responseData.data)
                if(responseData.data.tipo === 'e')
                    tabela(responseData.data);
                else
                    testeGrafico(responseData.data[0]);
            document.getElementById("resultado").style.display = "";

        }, error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function validarCamada() {
    let camada = $('.camadaOculta').val();
    if (camada == null || camada == isNaN || camada == '') {
        $(".camadaOculta").addClass('is-invalid');
        $(".errocamadaOculta").text("Preencha a Camada Oculta");
        $(".errocamadaOculta").removeClass("d-none");
        return false;
    } else if (camada < 0) {
        $(".camadaOculta").addClass('is-invalid');
        $(".errocamadaOculta").text("A Camada Oculta precisa ser maior que 0 ");
        $(".errocamadaOculta").removeClass("d-none");
    } else {
        $(".camadaOculta").removeClass('is-invalid');
        $(".errocamadaOculta").addClass('d-none');
        return true;
    }
}

function validarValorErro() {
    let valorErro = $('.valorErro').val();
    if (valorErro == null || valorErro == isNaN || valorErro == '') {
        $(".valorErro").addClass('is-invalid');
        $(".erroValorErro").text("Preencha o Valor do Erro");
        $(".erroValorErro").removeClass("d-none");
        return false;
    } else {
        $(".valorErro").removeClass('is-invalid');
        $(".erroValorErro").addClass('d-none');
        return true;
    }
}

function validarNumeroDeInt() {
    let numero = $('.numInt').val();
    if (numero == null || numero == isNaN || numero == '') {
        $(".numInt").addClass('is-invalid');
        $(".erroNumInt").text("Preencha o Valor do Número de Iterações");
        $(".erroNumInt").removeClass("d-none");
        return false;
    } else {
        $(".numInt").removeClass('is-invalid');
        $(".erroNumInt").addClass('d-none');
        return true;
    }
}

function validartxAprendizado() {
    let numero = $('.tx_Aprendizado').val();
    if (numero == null || numero == isNaN || numero == '') {
        $(".tx_Aprendizado").addClass('is-invalid');
        $(".errotx_Aprendizado").text("Preencha o Valor da Taxa de Aprendizado");
        $(".errotx_Aprendizado").removeClass("d-none");
        return false;
    } else if (numero < 0 || numero > 1) {
        $(".tx_Aprendizado").addClass('is-invalid');
        $(".errotx_Aprendizado").text("A Taxa de Aprendizado precisa entre 0 e 1");
        $(".errotx_Aprendizado").removeClass("d-none");
        return false;
    }
    else {
        $(".tx_Aprendizado").removeClass('is-invalid');
        $(".errotx_Aprendizado").addClass('d-none');
        return true;
    }
}

function validarForms() {
    let camadaOculta = validarCamada();
    let valorErro = validarValorErro();
    let numeroDeInt = validarNumeroDeInt();
    let txAprendizado = validartxAprendizado();

    if (camadaOculta && valorErro && numeroDeInt && txAprendizado)
        return true;

    return false;
}

function testeGrafico(valoresHistorico) {
    const ctx = document.getElementById('myChart2');
    // const labels = Utils.months({count: 7});
    const data = {
        labels: valoresHistorico,
        datasets: [{
            label: 'Histórico de erro da rede',
            data: valoresHistorico,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    };
    const config = {
        type: 'line',
        data: data,
    };
    new Chart(ctx,config);
}


function tabela(valores){
    valores = valores.data;
    let acertos = 0;
    for (let i = 0; i <valores.length; i++) {
        if(valores[i][0] === valores[i][1])
            acertos ++;
    }
    let acuracia = acertos / valores.length;
    let data = "<tr></tr>";
    data += "<tr>";
    data += "<th>Desejado</th>";
    data += "<th>Obtido</th>";
    data += "</tr>";
    for (let i = 0; i < valores.length; i++) {
        data += "<tr>";
        data += "<td>"+ valores[i][0] +"</td>";
        data += "<td>"+ valores[i][1] +"</td>";
        data += "</tr>";
    }
    document.getElementById('tabela').innerHTML = data;
    document.getElementById('result').innerText = ""+acuracia;
}