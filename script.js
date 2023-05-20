
$(document).ready(function () {
    // validarCamada();
});
$("#btn").click(function () {
    validarCamada();
});

function validarCamada() {
    var camada = $('.camadaOculta').val();
    console.log(camada)
    if (camada == null || camada == isNaN) {
        console.log("IF");
        $(".camadaOculta").addClass('is-invalid');
        $(".errocamadaOculta").text("Preencha a Camada Oculta");
        $(".errocamadaOculta").removeClass("d-none");
    } else {
        $(".camadaOculta").removeClass('is-invalid');
        $(".errocamadaOculta").addClass('d-none');
        console.log("Else");
    }
}

function validarValorErro() {

}

function validarNumeroDeInt() {

}

function validartxAprendizado() {

}

// function limparForms() {
//     let camadaOculta = validarCamada();
//     let valorErro = validarValorErro();
//     let numeroDeInt = validarNumeroDeInt();
//     let validartxAprendizado = validartxAprendizado();
//     if (camadaOculta)
//         return true;
//     return false;
// }

