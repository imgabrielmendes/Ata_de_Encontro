<?php

namespace formulario;
$id = $_GET['updateid'];
include_once("app/acoesform.php");
include("conexao.php");

$puxarform = new AcoesForm;
$facilitadores = $puxarform->selecionarFacilitadores();
$pegarfa = $puxarform->pegarfacilitador();
$puxaparticipantes = $puxarform->buscarParticipantesPorIdAta($id_ata = "?");
$puxadeliberacoes = $puxarform->buscarDeliberacoesPorIdAta($id_ata = "?");
$resultados = $puxarform->pegandoTudo();
$pegarid = $puxarform->puxarId();
$sql = "SELECT * FROM assunto WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $datasolicitada = $row['data_solicitada'];
    $tema = $row['tema'];
    $objetivo = $row['objetivo'];
    $local = $row['local'];
    $horainic = $row['hora_inicial'];
    $horaterm = $row['hora_termino'];

    // Código para obter os facilitadores associados à ATA
    $sql2 = "SELECT 
                fac.nome_facilitador as facilitadores,
                fac.id as idfacilitadores
            FROM ata_has_fac as ahf
            INNER JOIN facilitadores as fac ON fac.id = ahf.facilitadores
            WHERE ahf.id_ata = $id";
    $result2 = mysqli_query($conn, $sql2);
    $facilitadores = array(); 
    while ($row2 = mysqli_fetch_assoc($result2)) { 
        $facilitadores[] = $row2;
    }

$participantesArray = $pegarfa;

$pegarde=$puxarform->pegarfacilitador();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atareu";

//Cria a conexão
$conn = new \mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta SQL para selecionar os dados
// $sql = "SELECT id, data_registro, tema, data_solicitada, objetivo, hora_inicial, hora_termino, tempo_estimado, local, status FROM assunto ORDER BY data_registro DESC";
$result = $conn->query($sql);


function identificarIdPagina() {
  if(isset($_GET['updateid'])) {
      return $_GET['updateid'];
  } else {
      return null;
  }
}
$id_pagina = identificarIdPagina();


$puxatexto = $puxarform->textprinc($id_pagina);
$texto_principal = !empty($puxatexto) ? $puxatexto[0] : '';


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ata de encontro - HRG</title>
  <link rel="icon" href="view\img\Logobordab.png" type="image/x-icon">

  <script src="view/js/popper.min.js" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="view/css/styles.css">
  <link rel="stylesheet" href="view/css/bootstrap.min.css">
  <link rel="stylesheet" href="view/css/bootstrap-grid.css">
  <link rel="stylesheet" href="view/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="view/css/bootstrap.css">
  <link rel="stylesheet" href="view/css/selectize.bootstrap5.min.css">

  <link rel="stylesheet" href="view\css\multi-select-tag.css">


</head>

<body>

  <!--BARRA DE NAVEGAÇÃO-->
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-border-hrg">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://10.1.1.31:80/centralservicos/">
                <img src="http://10.1.1.31:80/centralservicos/resources/img/central-servicos.png" alt="Central de Serviço" style="width: 160px">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarCentral" aria-controls="navBarCentral" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navBarCentral">
            </div>
        </div>
    </nav>
    <div id="container" class="d-flex justify-content-between" style="background-color: #001f3f;">
        <div class="container_align d-flex align-items-center">
            <h2 id="tittle">Atribuição</h2>
        </div>
    </div>
</header>


  <!--FORMULÁRIO-->

  <!--PRIMEIRA LINHA DO FORMULÁRIO DA ATA---------------->
  <div class="box box-primary">
    <main class="container_fluid d-flex justify-content-center align-items-center">
      <div class="form-group col-8">
        <div class="row"> 
          
    <div class="accordion" id="accordionPanelsStayOpenExample">

      <div class="accordion-item shadow">
        <h2 class="accordion-header">
          <button class="accordion-button shadow-sm text-white" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color: #001f3f;">
            <h5>Informações de Registro</h5>
            <i class="fas fa-plus"></i>
          </button>
        </h2>
<div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
<div class="accordion-body" style="background-color: rgba(240, 240, 240, 0.41);">
        <div class="col-md-12 text-center"></div>    
            <div class="row">
                
                    <div class="col-sm-12 col-xl-3 col-md-6">
                        <label><b>Data:</b></label>
                        <ul class="form-control bg-body-secondary"><?php echo date('d/m/Y', strtotime($datasolicitada)); ?></ul>
                    </div>
                    <div class="col-sm-12 col-xl-3 col-md-6">
                        <label for="nomeMedico"><b>Horário de Início:</b></label>
                        
                        <ul class="form-control bg-body-secondary"><?php echo substr($horainic, 0, -3); ?></ul>
                    </div>
                    <div class="col-sm-12 col-xl-3 col-md-6">
                        <label for="form-control"> <b> Horário de Término:</b> </label>
                        <ul class="form-control bg-body-secondary"><?php echo substr($horaterm, 0, -3); ?></ul>
                    </div>
                    <div class="col-sm-12 col-xl-3 col-md-6">
                            <label for="form-control"><b>Tempo Estimado:</b></label>
                            <?php
                                $inicio = strtotime($horainic);
                                $termino = strtotime($horaterm);
                                $diferencaMinutos = ($termino - $inicio) / 60;
                                $horas = floor($diferencaMinutos / 60);
                                $minutos = $diferencaMinutos % 60;
                                $horas_formatado = sprintf("%02d", $horas);
                                $minutos_formatado = sprintf("%02d", $minutos);
                            ?>
                            <div class="form-control bg-body-secondary tempo-estimado"><?php echo $horas_formatado . ":" . $minutos_formatado . ":00"; ?></div>
                            <style>
                                .tempo-estimado {
                                width: 100%;
                            }
                        </style>
                    </div>
                </div>
                <div class="row">
                <div class="col-lg-6 col-lg-md-10 col-md-10">
                    <label><b>Facilitador(es):</b></label>
                    <ul class="form-control bg-body-secondary">
                        <?php
                        $facilitadoresString = "";
                        foreach ($facilitadores as $index => $facilitador) {
                            if ($index > 0) {
                                $facilitadoresString .= ", "; // Adiciona uma vírgula antes do facilitador, exceto para o primeiro facilitador
                            }
                            $facilitadoresString .= $facilitador['facilitadores']; // Concatena o nome do facilitador
                        }
                        echo $facilitadoresString;
                        ?>
                    </ul>
                </div>
                <div class="col-lg-3 col-lg-md-12 col-md-6">
                    <label><b>Local:</b></label>
                    <ul class="form-control bg-body-secondary border rounded"><?php echo $local; ?></ul>
                </div>
                <div class="col-lg-3 col-lg-md-12 col-md-6">
                    <label for="form-control"> <b>Objetivo:</b> </label>
                    <label class="form-control bg-body-secondary border rounded">
                    <input type="checkbox" disabled checked> <?php echo $objetivo; ?>
                    </label>
                </div>
                <div class="col-12">
                    <label><b>Tema:</b></label>
                    <ul class="form-control bg-body-secondary"><?php echo $tema; ?></ul>
                </div>            
            </div>
        </div>
    </div>
<!------------ACCORDION COM INFORMAÇÕES DE PARTICIPANTES---------------->

<form id="formSalvarInformacoes" method="post">
  <input type="hidden" id="idAta" name="idAta" value="">
  <div class="accordion">
    <div class="accordion-item shadow">
      <h2 class="accordion-header">
        <div class="accordion-button shadow-sm text-white" style="background-color: #1c8f69;;">
          <i class="fa-solid fa-circle-info"></i>
          <h5>Participantes</h5>
        </div>
      </h2>
      <main class="container-fluid ">
        <div class="row">
          
          <div class="col mt-3 mb-4">
            <div class="mb-2"> 
                <label for="form-control"><b>Adicione participantes</b></label>
            </div>
            
            <form id="addForm">
              <div class="col-12 form-group mb-3">
                  <div class="col">
                  <select class="col-8 form-control" id="participantesadicionado" name="facilitador" multiple data-id-ata="<?php echo isset($_GET['updateid']) ? $_GET['updateid'] : ''; ?>">
    <optgroup label="Selecione Facilitadores">
        <?php 
        $participantesNaAta = $puxarform->ParticipantesPorIdAta($_GET['updateid']);

        foreach ($pegarfa as $facilitador) {
            // Verifique se o facilitador não está na ATA
            $estaNaAta = false;
            foreach ($participantesNaAta as $participante) {
                if ($participante['id'] == $facilitador['id']) {
                    $estaNaAta = true;
                    break;
                }
            }
            
            if (!$estaNaAta) {
        ?>
            <option value="<?php echo $facilitador['id']; ?>" data-tokens="<?php echo $facilitador['nome_facilitador']; ?>">
                <?php echo $facilitador['nome_facilitador']; ?>
            </option>
        <?php 
            }
        } 
        ?>
    </optgroup>
</select>


                  </div>
              </div>
          </form>
          
          <div class="d-flex align-items-center">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#listaParticipantesModal" style="background-color: #001f3f; border-color: #001f3f;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1em; height: 1em; vertical-align: -0.125em;">
      <path fill="#ffffff" d="M96 0C60.7 0 32 28.7 32 64V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H96zM208 288h64c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16H144c-8.8 0-16-7.2-16-16c0-44.2 35.8-80 80-80zm-32-96a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM512 80c0-8.8-7.2-16-16-16s-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V80zM496 192c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm16 144c0-8.8-7.2-16-16-16s-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V336z"/> 
    </svg>  Participantes do encontro
  </button>
  <!-- <span class="ms-2">Participantes do encontro</span> -->
</div>



<div class="d-flex justify-content-center mt-3">
    <button id="registrarparticipantes" type="button" class="btn btn-primary" data-id-ata="<?php echo isset($_GET['updateid']) ? $_GET['updateid'] : ''; ?>">Registrar participante</button>
</div>
<script>
document.getElementById('registrarparticipantes').addEventListener('click', function() {
    var idAata = this.getAttribute('data-id-ata');
    var select = document.getElementById('participantesadicionado');
    var participantesSelecionados = [];
    for (var option of select.options) {
        if (option.selected) {
            participantesSelecionados.push(option.value);
        }
    }
    if (participantesSelecionados.length === 0) {
        Swal.fire({
            title: 'Atenção!',
            text: 'Por favor, selecione ao menos um participante.',
            icon: 'warning'
        });
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'particibanco.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            Swal.fire({
                title: 'Deu certo!',
                text: 'Participantes adicionados com sucesso.',
                icon: 'success',
                timer: 2500
            }).then(() => {
                location.reload();
            });
        } else if (xhr.readyState == 4) {
            Swal.fire({
                title: 'Erro!',
                text: 'Ocorreu um erro ao adicionar os participantes.',
                icon: 'error',
                timer: 2500
            }).then(() => {
                location.reload();
            });
        }
    }
    xhr.send('id_ata=' + idAata + '&participanteatribu=' + encodeURIComponent(JSON.stringify(participantesSelecionados)));
});
</script>



<div class="modal fade" id="listaParticipantesModal" tabindex="-1" aria-labelledby="listaParticipantesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title" id="listaParticipantesModalLabel">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" style="width: 1em; height: 1em; margin-right: 0.5em;">
        <path d="M72 88a56 56 0 1 1 112 0A56 56 0 1 1 72 88zM64 245.7C54 256.9 48 271.8 48 288s6 31.1 16 42.3V245.7zm144.4-49.3C178.7 222.7 160 261.2 160 304c0 34.3 12 65.8 32 90.5V416c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V389.2C26.2 371.2 0 332.7 0 288c0-61.9 50.1-112 112-112h32c24 0 46.2 7.5 64.4 20.3zM448 416V394.5c20-24.7 32-56.2 32-90.5c0-42.8-18.7-81.3-48.4-107.7C449.8 183.5 472 176 496 176h32c61.9 0 112 50.1 112 112c0 44.7-26.2 83.2-64 101.2V416c0 17.7-14.3 32-32 32H480c-17.7 0-32-14.3-32-32zm8-328a56 56 0 1 1 112 0A56 56 0 1 1 456 88zM576 245.7v84.7c10-11.3 16-26.1 16-42.3s-6-31.1-16-42.3zM320 32a64 64 0 1 1 0 128 64 64 0 1 1 0-128zM240 304c0 16.2 6 31 16 42.3V261.7c-10 11.3-16 26.1-16 42.3zm144-42.3v84.7c10-11.3 16-26.1 16-42.3s-6-31.1-16-42.3zM448 304c0 44.7-26.2 83.2-64 101.2V448c0 17.7-14.3 32-32 32H288c-17.7 0-32-14.3-32-32V405.2c-37.8-18-64-56.5-64-101.2c0-61.9 50.1-112 112-112h32c61.9 0 112 50.1 112 112z"/>
    </svg>
    Participantes do encontro
</h4>


<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="reloadPage()"></button>

<script>
  function reloadPage() {
    location.reload();
  }
</script>

      </div>
      <div class="modal-body">
      <?php
if (isset($_GET['updateid'])) {
    $id_ata = $_GET['updateid'];
    $participantes = $puxarform->buscarParticipantesPorIdAta($id_ata);
    if (!empty($participantes)) {
        // Ordena os participantes em ordem alfabética pelo nome do facilitador
        usort($participantes, function($a, $b) {
            return strcmp($a['nome_facilitador'], $b['nome_facilitador']);
        });

        echo "<table class='table'>";
        echo "<thead><tr><th>Matrícula</th><th>Nome</th><th>Email</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        foreach ($participantes as $participante) {
            // Aqui, você pode acessar os dados adicionais do facilitador usando $participante
            // Suponho que $participante já contenha os dados da tabela facilitadores
            echo "<tr id='participante-$id_ata-{$participante['nome_facilitador']}'>";
            echo "<td>{$participante['matricula']}</td>"; // Coluna de Matrícula
            echo "<td>{$participante['nome_facilitador']}</td>"; // Coluna de Nome
            echo "<td>{$participante['email_facilitador']}</td>"; // Coluna de Email
            // Botão de Excluir com chamada para a função JavaScript excluirParticipante
            echo "<td>
                <button type='button' class='btn btn-danger btn-sm' onclick='excluirParticipante($id_ata, \"{$participante['nome_facilitador']}\")'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' class='mr-2' style='width: 1em; height: 1em;'>
                        <path fill='#ffffff' d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/>
                    </svg>
                </button>
            </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "Nenhum participante encontrado para esta ATA.";
    }
} else {
    echo "Nenhum ID de ATA fornecido.";
}
?>
      </div>
    </div>
  </div>
</div>
<script>
function excluirParticipante(participanteId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "excluir participante.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Exclui visualmente a linha da tabela
            var row = document.getElementById('participante-' + participanteId);
            if (row) {
                row.parentNode.removeChild(row);
            } else {
                alert("Participante não encontrado na tabela.");
            }
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            alert("Erro ao excluir o participante.");
        }
    };
    xhr.send("participante_id=" + encodeURIComponent(participanteId));
}
</script>


<?php
$conn->close();
?>
</div>
</div>
</main>
</div>
</div>
</form>


<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("addForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
        var select = document.getElementById("participantesadicionado");
        var selectedOptions = select.selectedOptions;
        for (var i = 0; i < selectedOptions.length; i++) {
            var selectedOption = selectedOptions[i];
            var participante = selectedOption.textContent.trim();
            var participanteId = selectedOption.value;
            if (!participanteJaAdicionado(participante)) {
                adicionarParticipanteAoLabel(participante);
                selectedOption.remove(); 
            }
        }
    });
    $('#participantesadicionado').change(function() {
        var selected_ids = [];
        var selected_names = [];
        $('#participantesadicionado option:selected').each(function() {
            selected_ids.push($(this).val());
            selected_names.push($(this).text());
        });

        console.log(selected_ids);
        console.log(selected_names);
    });
});
function participanteJaAdicionado(participante) {
    var label = document.getElementById("participantesLabel");
    return label.textContent.includes(participante);
}
function adicionarParticipanteAoLabel(participante) {
    var label = document.getElementById("participantesLabel");
    var participanteItem = document.createElement("span");
    participanteItem.textContent = participante;
    participanteItem.classList.add("badge", "bg-secondary", "me-1");
    label.appendChild(participanteItem);
}
</script>

<!-----------------------------ACCORDION COM PARTICIPANTES-------------------------------->

<div class="accordion">
  <h2 class="accordion-header">
    <div class="accordion-button shadow-sm text-white" style="background-color: #66bb6a;">
      <h5>Texto principal</h5>
</div>
  </h2>

<!-----------------------------3° FASE-------------------------------->

<div class="accordion-collapse collapse show">
    <div class="accordion-body" style="background-color: rgba(240, 240, 240, 0.41);">
        <div class="col-md-12 text-center"></div>
        <div class="row">
        <div class="d-flex justify-content-center mt-3">
          <textarea id="textoprincipal" name="texto_principal" style="height: 110px;" class="form-control"><?php echo $texto_principal; ?></textarea>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <button id="registrarTextoButton" type="button" class="btn btn-primary" data-id-ata="<?php echo $id_ata; ?>">Registrar Texto</button>
        </div>
        <script>
        document.getElementById('registrarTextoButton').addEventListener('click', function() {
            var textoPrincipal = document.getElementById('textoprincipal').value;
            var idAata = this.getAttribute('data-id-ata');
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'textprincbanco.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if(xhr.readyState == 4 && xhr.status == 200) {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: 'O texto foi atualizado com sucesso.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        timer: 2500
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                } else if (xhr.readyState == 4) {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao atualizar o texto.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        timer: 2500
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                }
            }
            xhr.send('id_ata=' + idAata + '&textoprincipal=' + encodeURIComponent(textoPrincipal));
        });
        </script>
    </div>    
</div>


<!-----------------------------4° FASE-------------------------------->

<div class="accordion mt-4">
  <div class="accordion-item shadow">
    <h2 class="accordion-header">
      <div class="accordion-button shadow-sm text-white" style="background-color: #66bb6a;">
      <i class="fa-regular fa-pen-to-square p-1 mb-1"></i><h5>Deliberações Adicionadas</h5>
  </div></h2>
  
  <div class="accordion-collapse collapse show">
    <div class="accordion-body" style="background-color: rgba(240, 240, 240, 0.41);">
      <div class="col-md-12 text-center"></div>
      
      <div class="row">
        <div class ="col">
          <div id="existingDeliberations" class="mt-3">
                          <div id="deliberationsList" class="list-group">
                          <?php
                              $deliberacoes = $puxarform->buscarDeliberacoesPorIdAta($id_ata);
                              if (!empty($deliberacoes)) {
                                  $deliberacoesAgrupadas = [];
                                  foreach ($deliberacoes as $deliberacao) {
                                      $conteudo = $deliberacao['deliberacoes'];
                                      $deliberador = $deliberacao['deliberador'];
          
                                      if (!isset($deliberacoesAgrupadas[$conteudo])) {
                                          $deliberacoesAgrupadas[$conteudo] = [];
                                      }
          
                                      $deliberacoesAgrupadas[$conteudo][] = $deliberador;
                                  }
                                  $contagemdeli = 0;
                                  foreach ($deliberacoesAgrupadas as $conteudo => $deliberadores) {
                                      $contagemdeli++;
                                      $deliberadoresStr = implode(', ', $deliberadores);
                                      ?>
                                      <div style="margin-bottom: 15px;" data-conteudo="<?php echo $conteudo; ?>">
    <span class="col-2 badge rounded-pill text-bg-secondary mt-2 mb-2">
        <label for="" class="mb-1 mt-1">
            Deliberação N°<?php echo $contagemdeli; ?>
        </label>
    </span>
    <div class="row">
        <div class="row"></div>
        <div class="col-12">
            <li class="form-control bg-body-secondary border rounded">
                <div>
                    <strong>Deliberador:</strong> <?php echo $deliberadoresStr; ?>
                </div>
            </li>
        </div>
    </div>
    <div class="row align-items-center">
        <div class="col-11">
            <div class="form-control border rounded">
                <strong>Deliberação:</strong> <?php echo $conteudo; ?>
            </div>
        </div>
        <div class="col-1">
        <button class="btn btn-danger btn-sm delete-deliberacao mt-2" data-id-ata="<?php echo $id_ata; ?>" data-conteudo="<?php echo $conteudo; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="mr-2" style="width: 1em; height: 1em;">
                        <path fill="#ffffff" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                    </svg>
                </button>
            </div>
            </div>
            </div>
            <hr class="mt-3 mb-3 ">

                                     
                                      <?php
                                  }
                              }
                              ?>
                          </div>
                          <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                document.querySelectorAll('.delete-deliberacao').forEach(button => {
                                    button.addEventListener('click', function() {
          
                                        var idAta = this.getAttribute('data-id-ata');
                                        var conteudo = this.getAttribute('data-conteudo');
          
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', 'excluirdeli.php', true);
                                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                        xhr.onreadystatechange = function() {
                                            if(xhr.readyState == 4 && xhr.status == 200) {
                                                document.querySelector('div[data-conteudo="' + conteudo + '"]').remove();
                                                location.reload(); // Recarrega a página após a exclusão
                                            }
                                        };
                                        xhr.send('id_ata=' + encodeURIComponent(idAta) + '&conteudo=' + encodeURIComponent(conteudo));
                                    });
                                });
                            });
          
                          </script>
      </div>
      </div>   


</div>          
</div>
</div>
</div>




</script>
    <div class="accordion">
    <h2 class="accordion-header">
        <div class="accordion-button shadow-sm text-white" style="background-color: #66bb6a;">
            <h5>Adicione deliberação</h5>
        </div>
    </h2>
    <div class="accordion-collapse collapse show">
        <div class="accordion-body" style="background-color: rgba(240, 240, 240, 0.41);">
            <div class="col-md-12 text-center"></div>

            <span class="col d-flex align-items-end flex-column" id="inputContainer"></span>

            <form id="addForm">
                <div class="form-group">
                    <div class="col">
                        <div class="col">
                            
                            <ul class="list-group list-group-flush"></ul>
                            <textarea id="deliberacoes" class="form-control item" placeholder="Informe as deliberações..." style="height: 85px;" multiple data-id-ata="<?php echo isset($_GET['updateid']) ? $_GET['updateid'] : ''; ?>"></textarea>
                        </div>
                        <div class="col">
                            <div class="mb-2">
                            <select id="deliberador" class="form-control facilitator-select" placeholder="Deliberações" multiple>
                                <optgroup label="Selecione Facilitadores">
                                    <?php 
                                    $participantesNaAta = $puxarform->ParticipantesPorIdAta($id_ata);
                                    foreach ($pegarde as $facilitador) {
                                        if (in_array($facilitador['id'], array_column($participantesNaAta, 'id'))) {
                                    ?>
                                        <option value="<?php echo $facilitador['id']; ?>" data-tokens="<?php echo $facilitador['nome_facilitador']; ?>">
                                            <?php echo $facilitador['nome_facilitador']; ?>
                                        </option>
                                    <?php 
                                        }
                                    } 
                                    ?>
                                </optgroup>
                            </select>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div>
    <div class="row">
        <div class="col text-center">
            <div class="btn-container">
            <button id="reloadPageButton" type="button" class="btn btn-secondary">Inserir deliberação</button>
                <button id="atribuida" class="btn btn-primary">Finalizar encontro</button>
                <script>
        var botaoatribuicao = document.getElementById("atribuida");
        botaoatribuicao.addEventListener('click', gravaratribuida);

        function gravaratribuida() {
            var id_ata = document.getElementById("participantesadicionado").getAttribute("data-id-ata");
            console.log(id_ata);

            if (!id_ata) {
                console.error("id_ata não está definido.");
                return; 
            }

            Swal.fire({
                title: "Confirmação",
                text: "Após finalizado, o encontro não poderá ser alterado. Tem certeza de que deseja finalizar o encontro?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Sim"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'atualizar_status.php',
                        method: 'POST',
                        data: {
                            id_ata: id_ata,
                            status: 'FECHADA'
                        },
                        success: function(response) {
                            console.log("Status atualizado com sucesso.");

        
                            history.replaceState(null, null, 'paghistorico.php');
                             window.location.href = 'paghistorico.php';
                        },
                        error: function(error) {
                            console.error('Erro na solicitação AJAX:', error);
                        }
                    });
                }
            });
        }
        window.addEventListener('popstate', function(event) {
            history.pushState(null, null, window.location.href);
        });
    </script>
            </div>
            <script>
                document.getElementById('reloadPageButton').addEventListener('click', function() {
                var newItem = document.querySelector('.item').value.trim();
                var deliberadoresSelecionados = document.querySelector('.facilitator-select').selectedOptions;
                var deliberadoresSelecionadosLabel = Array.from(deliberadoresSelecionados).map(option => option.label);
                var deliberadoresSelecionadosNUM = Array.from(deliberadoresSelecionados).map(option => option.value);
                var idAata = document.querySelector('.item').getAttribute('data-id-ata');

                if (newItem === "" && deliberadoresSelecionadosLabel.length === 0) {
                    Swal.fire({
                        title: "Preencha os campos de deliberação",
                        icon: "error"
                    });
                    return;
                } else if (newItem === "") {
                    Swal.fire({
                        title: "Você não adicionou uma deliberação",
                        text: "Adicione pelo menos 1 deliberação para a ata",
                        icon: "error"
                    });
                    return;
                } else if (deliberadoresSelecionadosLabel.length === 0) {
                    Swal.fire({
                        title: "Você não adicionou um deliberador",
                        text: "Adicione pelo menos 1 deliberador para a deliberação",
                        icon: "error"
                    });
                    return;
                } else {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'enviardeli.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if(xhr.readyState == 4 && xhr.status == 200) {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: 'A deliberação foi inserida com sucesso.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                }
                            });
                        } else if (xhr.readyState == 4) {
                            Swal.fire({
                                title: 'Erro!',
                                text: 'Ocorreu um erro ao inserir a deliberação.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                timer: 2500
                            }).then((result) => {
                                if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                }
                            });
                        }
                    }
                    xhr.send('id_ata=' + encodeURIComponent(idAata) + '&deliberaDores=' + JSON.stringify(deliberadoresSelecionadosNUM) + '&newItem=' + encodeURIComponent(newItem));
                    }
                });
            </script>
        </div>
    </div>
</div>



<div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="view/img/check.svg" class="rounded me-2" alt="..." style="width: 20px";>
          <strong class="me-auto">Perfeito!</strong>
          <small>Agora</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          A deliberação foi atribuída.
        </div>
      </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="liveToast2" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="view\img\x.svg" class="rounded me-2" alt="..." style="width: 15px";>
          <strong class="me-auto">Perfeito!</strong>
          <small>Agora</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          Atribuição excluída.
        </div>
      </div>
    </div>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="view/js/bootstrap.js"></script>
    <script src="view/js/multi-select-tag.js"></script>
    <script src="app/pagdeliberacoes.js"></script>
    <script src="app/deliberacoes.js"></script>
    <script src="app/gravaratribuida.js" data-id-ata="<?php echo $id_ata; ?>"></script>
    <script src="app/excluiratribuida.js"></script>
    
    
</body>
</html>