<?php
namespace formulario;
session_start();
include_once ("app/acoesform.php");
include ("conexao.php");

$puxarform = new AcoesForm;
$pegarde = $puxarform->pegarfacilitador();

$ultimosfacilitadores = $puxarform->puxandoUltimosFacilitadores();


$facilitadoresString = '';
foreach ($ultimosfacilitadores as $facilitador) {
    $facilitadoresString .= $facilitador['nome_facilitador'] . ', ';
}

$facilitadoresString = rtrim($facilitadoresString, ', ');

$ultimaata = $puxarform->pegarUltimaAta();
$data = $_SESSION['data'];
$dateTime = new \DateTime($data);
$data_formatada = $dateTime->format('d/m/Y');
$_SESSION['data'] = $data_formatada;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ata de encontro - HRG</title>
  <link rel="icon" href="view\img\Logobordab.png" type="image/x-icon">

  <!---------------------------------------------------------------->
  <script src="view/js/popper.min.js" crossorigin="anonymous"></script>

  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-JCHjo1FjBu5zj08fFZ8niXNt6IuPO3WJ10Ii+XXITZ7IU46Scij9MJTf/ZZTK5HVm/BwOxAnoxO8cSvDaz9VWg==" crossorigin="anonymous" /> -->

  <!-- <link rel="stylesheet" href="view/fontawesome/css/fontawesome.css"> -->


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="view/css/styles.css">
  <link rel="stylesheet" href="view/css/bootstrap.min.css">
  <link rel="stylesheet" href="view/css/bootstrap-grid.css">
  <link rel="stylesheet" href="view/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="view/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="view/css/selectize.bootstrap5.min.css">

  <link rel="stylesheet" href="view\css\multi-select-tag.css">


</head>

<body>

  <!--BARRA DE NAVEGAÇÃO-->
  <style>
          body{
        background-color: rgba(240, 240, 240, 0.41);
      }

  .content-header{
        background-color: #001f3f;
    }
    </style>
  <header>
      <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-border-hrg">
            <div class="container-fluid">
                <a class="navbar-brand" href="http://10.1.1.31:80/centralservicos/"><img src="http://10.1.1.31:80/centralservicos/resources/img/central-servicos.png" alt="Central de Serviço" style="width: 160px">
                </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarCentral" aria-controls="navBarCentral" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
      

      <div class="collapse navbar-collapse" id="navBarCentral">
      </div>
    </div>
  </nav>
  <div class="content-header shadow" style="border-bottom: solid 1px gray;">
      <div class="container-fluid">
        <div class="row py-1">
          <div class="col-sm-6">
            <h2 class="m-3 text-light shadow"><i class="fas fa-users"></i> Registro de Encontro</h2>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!--FORMULÁRIO-->

  <!--PRIMEIRA LINHA DO FORMULÁRIO DA ATA---------------->
  <div class="box box-primary">
    <main class="container_fluid d-flex justify-content-center align-items-center">
      <div class="form-group col-xl-9 col-lg-xs-sm-md-12">
        <div class="row"> 
          
    <div class="accordion" id="accordionPanelsStayOpenExample">

      <div class="accordion-item shadow">
        <h2 class="accordion-header">
          <button class="accordion-button shadow-sm text-white" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color: #001f3f;">
          <i class="fa-solid fa-circle-info p-1 mb-1"></i><h5>Informações de Registro</h5>
           
          </button>
        </h2>

    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse ">
      <div class="accordion-body" style="background-color: rgba(240, 240, 240, 0.41);">
          <div class="col-md-12 text-center">         
          </div>     

          <!---- PRIMEIRA LINHA DO REGISTRO ---->
          <div class="row">
    <br>
    <div class="col-sm-12 col-xl-3  col-md-6">
        <label><b>Data*:</b></label>
        <ul class="form-control bg-body-secondary"> <?php echo $_SESSION['data']; ?> </ul>
    </div>

    <!---ABA DE HORÁRIO INICIO---->
    <div class="col-sm-12 col-xl-3  col-md-6">
        <label for="nomeMedico"><b>Horário de Início*:</b></label>
        <br>
        <ul class="form-control bg-body-secondary"><?php echo $_SESSION['horainicio']; ?></ul>
    </div>

    <!---ABA DE HORÁRIO TERMINO---->
    <div class="col-sm-12 col-xl-3  col-md-6">
        <label for="form-control"> <b> Horário de Término:</b> </label>
        <ul class="form-control bg-body-secondary"><?php echo $_SESSION['horaterm']; ?></ul>
    </div>

    <!---ABA DE TEMPO ESTIMADO ---->
    <div class="col-sm-12 col-xl-3  col-md-6">
        <label for="form-control"><b>Tempo Estimado:</b></label>
        <?php
        // Verifica se as variáveis estão definidas antes de calcular o tempo estimado
        if (isset($_SESSION['horainicio']) && isset($_SESSION['horaterm'])) {
            // Calcula a diferença de tempo em minutos
            $inicio = strtotime($_SESSION['horainicio']);
            $termino = strtotime($_SESSION['horaterm']);
            $diferencaMinutos = ($termino - $inicio) / 60;

            // Calcula horas e minutos
            $horas = floor($diferencaMinutos / 60);
            $minutos = $diferencaMinutos % 60;

            // Formata os números com dois dígitos
            $horas_formatado = sprintf("%02d", $horas);
            $minutos_formatado = sprintf("%02d", $minutos);

            // Exibe o tempo estimado no formato "00:00"
            echo "<div class='form-control bg-body-secondary tempo-estimado'>" . $horas_formatado . ":" . $minutos_formatado . ":00". "</div>";
        } else {
            echo "Horário de início e/ou término não definidos.";
        }
        ?>
        <style>
            .tempo-estimado {
                width: 100%;
            }
        </style>
    </div>
</div>

          <div class="row">
          <div class="col-lg-6  col-lg-md-12 col-md-12">
        <label><b>Facilitador(es):</b></label>
        <ul class="form-control bg-body-secondary"><?php echo $facilitadoresString; ?></ul>
    </div>
          
 
    <div class="col-lg-3  col-lg-md-12 col-md-6">
        <label><b>Local:</b></label>
        <ul class="form-control bg-body-secondary border rounded"><?php echo $_SESSION['local']; ?></ul>
    </div>
    <div class="col-lg-3  col-lg-md-12 col-md-6">
        <label for="form-control"> <b>Objetivo:</b> </label>
        <label class="form-control bg-body-secondary border rounded">
            <input type="checkbox" disabled checked> <?php echo $_SESSION['objetivoSelecionado']; ?>
    </div>
    <div>
        <div class="col">
            <b>Tema:</b> 
        </div>
        <div>
            <div class="col-12">
                <ul class="form-control bg-body-secondary"><?php echo $_SESSION['conteudo']; ?></ul>
            </div>
        </div>       
    </div>
</div>
      </div>
    </div>
</div>
  </div>
<!------------ACCORDION COM INFORMAÇÕES DE PARTICIPANTES---------------->
<div class="accordion mt-4" id="accordionPanelsStayOpenExample">

<div class="accordion-item shadow">
  <h2 class="accordion-header">
    <button class="accordion-button shadow-sm text-white" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo" style="background-color: #1c8f69;">

    <i class="fas"></i>
    <i class="fa-solid fa-user p-1 mb-1"></i><h5>Participantes Adicionados </h5>

    </button>
  </h2>

<div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse ">
<div class="accordion-body" style="background-color: rgba(240, 240, 240, 0.41);">
    <div class="col-md-12 text-center">         
          
    </div>     







    <div class="row">
    <div class="col">
        <div>
            <div style="margin: 6px" class='form-control bg-body-secondary border rounded'>
                <ul>
                <?php
                  if (isset($_GET['updateid'])) {
                      $id_ata = $_GET['updateid'];
                      $participantes = $puxarform->buscarParticipantesPorIdAta($id_ata);
                      if (!empty($participantes)) {
                          echo "<ul class='list-unstyled'>";
                          foreach ($participantes as $participante) {
                              echo "<li>";
                              echo "<span class='fw-bold' style='font-size: 18px;'>$participante</span>";
                              echo "<button type='button' class='btn btn-danger btn-sm ms-2 py-0' style='font-size: 12px;' onclick='excluirParticipante($id_ata, \"$participante\")'>Excluir</button>";
                              echo "</li>";
                          }
                          echo "</ul>";
                      } else {
                          echo "Nenhum participante encontrado para esta ATA.";
                      }
                  } else {
                      echo "Nenhum ID de ATA fornecido.";
                  }
                ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
  function excluirParticipante(participante) {
    if (confirm("Tem certeza de que deseja excluir o participante '" + participante + "'?")) {
      var participanteElement = document.querySelector("li:contains('" + participante + "')");
      if (participanteElement) {
        participanteElement.remove();
      } else {
        alert("Participante não encontrado na lista.");
      }
    }
  }
</script>






</div>
</div>
</div>
</div>
  </div>



  <!-- texto principal -->




  <div class="accordion mt-4">
<div class="accordion-item shadow">
  <h2 class="accordion-header">
    <div class="accordion-button shadow-sm text-white" style="background-color: #66bb6a;">
    <i class="fa-regular fa-pen-to-square p-1 mb-1"></i><h5>Descrição do Encontro</h5>

</div>
  </h2>

<!-----------------------------4° FASE-------------------------------->

<div class="accordion-collapse collapse show">
<div class="accordion-body" style="background-color: rgba(240, 240, 240, 0.41);">
    <div class="col-md-12 text-center">               
    </div>
    <div class="row">
    <div class ="col">
        <label style="height: 35px;"><b>Informe o texto principal:</b></label>
        <textarea id="textoprinc" style="height: 110px;" class="form-control"></textarea>

              </div>
    </div>   
        
    <div class="d-flex justify-content-center">
            <button id="abrirhist" type="button" class="btn btn-primary" data-bs-toggle="modal">Registrar Texto</button>
        </div>

            </div>          
</div>

  </div>
    </div>

<!-----------------------------ACCORDION COM PARTICIPANTES-------------------------------->

<div class="accordion mt-4">
<div class="accordion-item shadow">
  <h2 class="accordion-header">
    <div class="accordion-button shadow-sm text-white" style="background-color: #66bb6a;">
    <i class="fa-solid fa-file p-1 mb-1"></i><h5>Deliberações</h5>

</div>
  </h2>

<!-----------------------------4° FASE-------------------------------->

<div class="accordion-collapse collapse show">
<div class="accordion-body" style="background-color: rgba(240, 240, 240, 0.41);">
    <div class="col-md-12 text-center">               
    </div>
 
    <span class="col d-flex align-items-end flex-column" id="inputContainer"></span>

        <form id="addForm">
        <div class="form-group">
        <div class="col">
          
              <br>
              <ul class="list-group list-group-flush"></ul>              
              <textarea id="deliberacoes" class="form-control item" placeholder="Informe as deliberações..." style="height: 85px;"></textarea>
            </div>

    <div class="col">
    <!-- Primeira caixa de texto e select de facilitadores -->
    <div class="mb-2">
        <label for="" class="mb-2">Deliberado para:</label>
        <select id="deliberador" class="form-control facilitator-select" placeholder="Deliberações" multiple>
        <optgroup label="Selecione Facilitadores">
                  <?php foreach ($pegarde as $facnull) : ?>
                      <option value="<?php echo $facnull['id']; ?>"
                          data-tokens="<?php echo $facnull['nome_facilitador']; ?>">
                          <?php echo $facnull['nome_facilitador']; ?>
                      </option>
                  <?php endforeach ?>
              </optgroup>
        </select>
    </div>
        </div>
        <div class="col-12">
          <ul id="caixadeselecaodel"></ul>
  <div class="col d-flex justify-content-center align-content-center">
    
    <button type="button" id="addItemButton" class="btn btn-success  a">Criar deliberações</button>
  </div>
</div>

  </div>
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="liveToast3" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="view/img/check.svg" class="rounded me-2" alt="..." style="width: 20px";>
          <strong class="me-auto">Perfeito!</strong>
          <small>Agora</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
         Descrição de encontro adicionado!
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

        <br>
        
        <div class="col-12">

        <!-- <button id="" type="button" class="btn btn-primary" data-bs-toggle="modal"> Atualizar a ata </button> -->
        <div class=" col d-flex justify-content-center align-content-center">
           <button id="finalizarAtaBtn" type="button" class="btn btn-secondary" data-bs-toggle="modal">Finalizar Encontro</button>
<script>  
document.getElementById("finalizarAtaBtn").addEventListener("click", function() {
    Swal.fire({
        title: "Finalizada!",
        text: "Você finalizou seu encontro com sucesso!",
        icon: "success",
        confirmButtonText: "OK"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "paghistorico.php";
        }
    });


});</script>
          

        </div></div>

    </form>
          
            </div>          
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var botaocont = document.getElementById('botaocontinuarata');
        var botaoregistrar = document.getElementById('botaoregistrar');
        var itemList = document.getElementById('items');
        var filter = document.getElementById('filter');
        var addItemButton = document.getElementById('addItemButton'); 

    });

</script>
  </div>
    </div>
      </div>
         </div>
         
</main>

</div>
       
    <script src="view\js\multi-select-tag.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="view/js/bootstrap.js"></script>
    <script src="app/pagdeliberacoes.js"></script>
    <script src="app/excluiratribuida.js"></script>

</body>

</html>