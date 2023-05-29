$("#btn").click(function () {
    var formData = new FormData($('#fdados')[0]);
    if (validarForms()) {
        enviarForms(formData);
        // testeGrafico();
    }
});

function enviarForms(formData) {
    $.ajax({
        url: "",//passar a URL
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
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


// function importarArquivo(event) {
//     var file = event.target.files[0];

//     var reader = new FileReader();
//     reader.onload = function (e) {
//         var contents = e.target.result;
//         displayCSV(contents);
//     };
//     reader.readAsText(file);
// }
// function displayCSV(contents) {
//     var lines = contents.split("\n");
//     var table = document.createElement("table");

//     for (var i = 0; i < lines.length; i++) {
//         var row = document.createElement("tr");
//         var cells = lines[i].split(",");

//         for (var j = 0; j < cells.length; j++) {
//             var cell = document.createElement("td");
//             cell.textContent = cells[j];
//             row.appendChild(cell);
//         }

//         table.appendChild(row);
//     }
//     document.body.appendChild(table);

// }

function testeGrafico() {



    // document.getElementById("tituloResultado").innerHTML += '<label>Resultado</label>';

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    document.getElementById("resultado").style.display = "";
}


