var facilitadoresSelecionados = []; // Definido fora da função gravando()

new MultiSelectTag('selecionandofacilitador', {
    rounded: true, 
    shadow: false,     
    placeholder: 'Search', 
    tagColor: {
        textColor: '#1C1C1C',
        borderColor: '#4F4F4F',
        bgColor: '#F0F0F0',
    },
    onChange: function(values) {
        console.log(values);
        facilitadoresSelecionados = values; // Atribuição dos valores
    }
});

// Pegar inputs 
var gravarinformacoes = document.getElementById("botaoregistrar");

//1° LINHAS
var data = document.getElementById("datainicio");
var horainicio = document.getElementById("horainicio").value;
var horaterm = document.getElementById("horaterm").value;
// var tempoes = document.getElementById("tempoestim").value;

// 2° LINHAS
var objetivomarc = document.getElementsByName("objetivo");
var objetivoSelecionado = null;

//3° LINHA
var temaprincipal = document.getElementById("temaprincipal");

// Botões
gravarinformacoes.addEventListener('click', gravando);

function gravando() {

    var data = document.getElementById("datainicio").value;
    var horainicio = document.getElementById("horainicio").value;
    var horaterm = document.getElementById("horaterm").value;
    // var tempoes = document.getElementById("tempoestim").value;

    var objetivomarc = document.getElementsByName("objetivo");
    var objetivoSelecionado = null;

    for (var op = 0; op < objetivomarc.length; op++) {
        if (objetivomarc[op].checked) {
            objetivoSelecionado = objetivomarc[op].value;
            break;
        }
    }

    var local = document.getElementById("pegarlocal").value;
    // var facilitadores = document.getElementById("selecionandofacilitador").value;
    var temaprincipal = document.getElementById("temaprincipal");

    var conteudo = temaprincipal.value;
    var data = document.getElementById("datainicio").value;

    if (data.trim() === "" || horainicio.trim() === "" || objetivoSelecionado.trim() === "" || conteudo.trim() === "" ) {

        Swal.fire({
            title: "Erro no registro",
            text: "Preencha todas as caixas obrigatórias",
            icon: "error"
        });

        console.log("(X) Puxou a function, mas está faltando informações");
        console.log(objetivoSelecionado);
        console.log(local);
        console.log(facilitadores);

    } 
    
    else {

        Swal.fire({
            title: "Ata registrada com sucesso!",
            icon: "success"
        });
    }

    console.log("(1) A função 'gravando()' foi chamada");

    // Primeira solicitação AJAX para enviarprobanco.php
    $.ajax({
        url: 'enviarprobanco.php',
        method: 'POST',
        data: {
            facilitadores: JSON.stringify(facilitadoresSelecionados),
            texto: conteudo,
            horai: horainicio,
            horat: horaterm,
            datainic: data,
            objetivos: objetivoSelecionado,
            local: local,
            // tempoestimado: tempoes,
        },
        
        success: function () {
            console.log("(2) Deu bom! AJAX está enviando");

            setTimeout(function() {
                window.location.href = 'pagparticipantes.php' +
                '?facilitadores=' + encodeURIComponent(JSON.stringify(facilitadoresSelecionados)) +
                '&conteudo=' + encodeURIComponent(conteudo) +
                '&horainicio=' + encodeURIComponent(horainicio) +
                '&horaterm=' + encodeURIComponent(horaterm) +
                '&data=' + encodeURIComponent(data) +
                '&objetivoSelecionado=' + encodeURIComponent(objetivoSelecionado) +
                '&local=' + encodeURIComponent(local);
            }, 1500);
        },

        error: function (error) {
            console.error('Erro na solicitação AJAX:', error);
            console.log(facilitadores);
        },
    });
}
