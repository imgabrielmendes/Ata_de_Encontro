<?php
namespace formulario;

require_once __DIR__.'/vendor/autoload.php';
include "conexao.php";
require_once 'app/acoesform.php';


$id = $_GET['updateid'];
$puxarform = new AcoesForm;
$pegarfa = $puxarform->pegarfacilitador();
$pegarlocal = $puxarform->pegarlocais();

$sql = "SELECT * FROM assunto WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$datasolicitada = date('Y-m-d', strtotime($row['data_solicitada']));
$tema = $row['tema'];
$objetivo = $row['objetivo'];
$local = $row['local'];
$horainic = date('H:i', strtotime($row['hora_inicial']));
$horaterm = date('H:i', strtotime($row['hora_termino']));

$sql2 = "SELECT 
    fac.nome_facilitador AS facilitadores,
    fac.id AS idfacilitadores
    FROM ata_has_fac AS ahf
    INNER JOIN facilitadores AS fac ON fac.id = ahf.facilitadores
    WHERE ahf.id_ata = $id";

$result2 = mysqli_query($conn, $sql2);
$facilitadores = array(); 

while ($row2 = mysqli_fetch_assoc($result2)) { 
    $facilitadores[] = $row2;
}

$sql3 = "SELECT 
    del.id_ata,
    fac.nome_facilitador AS deliberador,
    del.deliberacoes AS deliberacoes
    FROM atareu.deliberacoes AS del
    INNER JOIN atareu.facilitadores AS fac ON fac.id = del.deliberadores
    WHERE del.id_ata = $id";

$result3 = mysqli_query($conn, $sql3);
$deliberacoes_array = array();
$deliberador_array = array();

if ($result3 && mysqli_num_rows($result3) > 0) {
    while ($row3 = mysqli_fetch_assoc($result3)) {
        $deliberacoes_array[] = $row3['deliberacoes'];
        $deliberador_array[] = $row3['deliberador'];
    }
}


$sql4= "SELECT 
    ass.id,
    txt.id_ata,
    txt.texto_princ AS textoprincipal
    FROM atareu.textoprinc AS txt
    INNER JOIN atareu.assunto AS ass
    ON ass.id = txt.id_ata
    WHERE txt.id_ata = $id";

$result4 = mysqli_query($conn, $sql4);
$textoprincipal = '';

if ($row4 = mysqli_fetch_assoc($result4)) { 
    $textoprincipal = $row4['textoprincipal'];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ATA</title>
    <link rel="icon" href="view/img/Logobordab.png" type="image/x-icon">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="view/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/css/multi-select-tag.css">
    <script src="view/js/multi-select-tag.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="view/js/bootstrap.js"></script>
 
    <style>
        body {
            background-color: rgba(240, 240, 240, 0.41);
        }
        .content-header {
            background-color: #001f3f;
        }
    </style>
</head>
<body>
<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-border-hrg">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://10.1.1.31:80/centralservicos/"><img src="http://10.1.1.31:80/centralservicos/resources/img/central-servicos.png" alt="Central de Serviço" style="width: 160px">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarCentral" aria-controls="navBarCentral" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navBarCentral"></div>
        </div>
  </nav>
  <div class="content-header" style="border-bottom: solid 1px gray;">
      <div class="container-fluid">
        <div class="row py-1">
          <div class="col-sm-6">
            <h2 class="m-3 text-light shadow"><i class="fas fa-users"></i> Histórico de atas de encontro</h2>
          </div>
        </div>
      </div>
    </div>
</header>
<form method="POST" id="" action="">
    <main class="container_fluid d-flex justify-content-center align-items-center">
      <div class="form-group col-8">
        <div class="row"> 
          <div class="col-md-12 text-center p-5">
            <h2>INFORMAÇÕES - ATA N°<?php echo $id ?></h2>
          </div>
          <div class="col-lg-6 col-xl-3 col-md-6 col-sm-6">
            <label><b>Data</b></label>
            <input id="datainicio" class="form-control bg-body-secondary" placeholder="dd-mm-aaaa" min="2024-04-01" type="date" value="<?php echo $datasolicitada ?>" readonly>
          </div>
          <div class="col-lg-6 col-xl-3 col-md-6 col-sm-6">
            <label for="nomeMedico"><b>Horário de Início:</b></label>
            
            <input class="form-control bg-body-secondary" type="time" id="horainicio" name="appt" min="" max="18:00" value="<?php echo $horainic ?>" readonly>
          </div>
          <div class="col-lg-6 col-xl-3 col-md-6 col-sm-6">
            <label for="form-control"> <b> Horário de Término:</b> </label>
            <input class="form-control bg-body-secondary" type="time" id="horaterm" name="appt" min="13:00" max="12:00" value="<?php echo $horaterm ?>" readonly>
          </div>
          <div class="col-lg-6 col-xl-3 col-md-6 col-sm-6">
            <label for="form-control"> <b> Tempo Estimado (horas):</b> </label>
            <input value="1" class="form-control bg-body-secondary" type="number" id="tempoestim" name="appt" min="0" max="5" readonly>
          </div>
          <div class="col-sm-6 col-lg-6 col-md-6 col-xl-2 mt-2" >
            <label for="objetivo pb-2"> <b>Objetivo:</b> </label>
            <select class="form-control" name="objetivo" id="objetivo">
                <option value="Reunião" <?php if ($objetivo == 'Reunião') echo 'selected'; ?>>Reunião</option>
                <option value="Treinamento" <?php if ($objetivo == 'Treinamento') echo 'selected'; ?>>Treinamento</option>
                <option value="Consulta" <?php if ($objetivo == 'Consulta') echo 'selected'; ?>>Consulta</option>
                <?php if (empty($objetivo)) : ?>
                    <option selected disabled hidden>Objetivo não informado</option>
                <?php endif; ?>
            </select>
          </div>
          <div class="col-sm-6 col-md-6 col-xl-5 col-lg-6 mt-2 pb-2">
          <label for="local"><b>Local:</b></label>
          <select class="form-control" name="local" id="local">
              <?php echo empty($pegarlocal) ? '<option selected disabled hidden>Local não informado</option>' : ''; ?>
              <?php foreach ($pegarlocal as $locais) : ?>
                  <option value="<?php echo $locais['locais']; ?>" <?php echo ($local == $locais['locais']) ? 'selected' : ''; ?>>
                      <?php echo $locais['locais']; ?>
                  </option>
              <?php endforeach; ?>
          </select>
      </div>
          <div class="col-md-5 col-xl-5 col-lg-12 col-sm-6 mt-2 pb-2"><b>Tema:</b>
            
            <input id="temaprincipal" class="form-control bg-body-secondary" type="text" value="<?php echo $tema ?>" readonly/>
          </div>
          <div class="row">
            <div class="col-4 pt-2 pb-2"> 
              <label for="form-control"> <b> Facilitador(res) responsável*:</b> </label> 
            </div>
          </div>
          <select class="col-6 form-control" id="selecionandofacilitador" name="facilitador" multiple value="">
            <optgroup label="Selecione Facilitadores">
                <?php foreach ($pegarfa as $facnull) : ?>
                    <option value="<?php echo $facnull['id']; ?>" data-tokens="<?php echo $facnull['nome_facilitador']; ?>">
                        <?php echo $facnull['nome_facilitador']; ?>
                    </option>
                <?php endforeach ?>
            </optgroup>
          </select>
          <div class="col-6 form-control mt-2">
            <ul>
                <?php foreach ($facilitadores as $facilitador): ?>
                    <li><?php echo $facilitador['facilitadores']; ?></li>
                <?php endforeach; ?>
            </ul>   
          </div>


    <input type="hidden" name="form_action" value="delete">
    <input type="hidden" name="delete_id" value="<?php echo $_GET['updateid']; ?>">
    
    <p class="pt-2"><b>Texto Principal:</b></p>
      <div class="row">
        <div class="col">
          <input class="form-control bg-body-secondary" value="<?php echo $textoprincipal;?>" >
        </div> 
      </div>

    <label class="h4 pt-5"><b>DELIBERAÇÕES</b></label>
    <?php foreach ($deliberacoes_array as $index => $deliberacao) : ?>
    <div class="row pt-3">
        <div class="col-4">
            <input class="form-control" value="<?php echo $deliberador_array[$index]?>" >
        </div> 
        <input class="col form-control" value="<?php echo $deliberacao ?>" >
    </div>
    <?php endforeach; ?>
    
</form>

<form id="seu-formulario-id">
    <input type="hidden" name="id" value="<?php echo $_GET['updateid']; ?>">
    <div class="row">
        <div class="col-3 mt-3">
            <div class="btn-atas">
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
        </div>
    </div>
</form>

          <div class="row">
            <footer class="col main-footer p-4" style="margin-left: 0 !important; margin-top: 1em;">
              <strong>Copyright © 2021 <a href="http://www.hospitalriogrande.com.br/" target="_blank">Hospital Rio Grande</a></strong>. Todos os direitos reservados.
              <div class="float-right d-none d-sm-inline-block">
                <b>Versão</b> 0.0.1
            </footer>
          </div>
        </div>
      </div>
    </main>

    <script>

      new MultiSelectTag('selecionandofacilitador', {
            rounded: true, 
            shadow: false,     
            placeholder: 'Search', 
            tagColor: {
                textColor: '#1C1C1C',
                borderColor: '#4F4F4F',
                bgColor: '#F0F0F0',
            },
            onChange: function(selected_ids, selected_names) {
                facilitadoresSelecionados = selected_ids;
                facilitadoresSelecionadosLabel = selected_names;
                // console.log(facilitadoresSelecionados);
                // console.log(facilitadoresSelecionadosLabel);
            }
      });
      document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById("seu-formulario-id");

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var objetivo = document.getElementById('objetivo').value;
        var local = document.getElementById('local').value;
        var id = <?php echo json_encode($_GET['updateid']); ?>;

        if (objetivo === "" || local === "") {
            window.alert("Preencha as informações");
        } else {
            $.ajax({
                type: 'POST',
                url: 'acoesdeupdate.php',
                data: {
                    objetivo: objetivo,
                    id: id,
                    local: local,
                },
                success: function(response) {
                    alert(response);
                    console.log("DEU CERTO");
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    console.log("DEU RUIM");
                }
            });
        }
    });
});
</script>
</body>
</html>
