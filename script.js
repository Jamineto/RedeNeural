
$(document).ready(function () {
    // validarCamada();
});
$("#btn").click(function () {
    if (validarForms()) {
        //faço alguma coisa
    }
});

function validarCamada() {
    let camada = $('.camadaOculta').val();
    if (camada == null || camada == isNaN || camada == '') {
        $(".camadaOculta").addClass('is-invalid');
        $(".errocamadaOculta").text("Preencha a Camada Oculta");
        $(".errocamadaOculta").removeClass("d-none");
        return false;
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
    } else {
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

