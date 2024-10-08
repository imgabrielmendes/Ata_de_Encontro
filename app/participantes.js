var ParticipantesAdicionadosvalor = [];
var participantesAdicionadosLabel = [];

new MultiSelectTag('participantesadicionados', {
    rounded: true, 
    shadow: false,     
    placeholder: 'Search', 
    tagColor: {
        textColor: '#1C1C1C',
        borderColor: '#4F4F4F',
        bgColor: '#F0F0F0',
    },
    
    onChange: function(selected_ids, selected_names) {

        ParticipantesAdicionadosvalor = selected_ids;
        participantesAdicionadosLabel = selected_names;

        // console.log(ParticipantesAdicionadosvalor);
        // console.log(participantesAdicionadosLabel);
    }
    
});

console.log();

var botaocont = document.getElementById('botaocontinuarata');

var itemList = document.getElementById('items');
var filter = document.getElementById('filter');
var addItemButton = document.getElementById('addItemButton');
var mensagemInfo = document.getElementById('infoMessage');

botaocont.addEventListener('click', addDeliberacoes);

function addDeliberacoes() {

    if (participantesAdicionadosLabel.length === 0 || participantesAdicionadosLabel.length === 0) 
        {
            
            Swal.fire({
                title: "Erro no registro",
                text: "Você não adicionou os seus participantes",
                icon: "error"
              });
       
        } else {
            Swal.fire({
            title: "Perfeito!",
            text: "Seus participantes foram registrados",
            icon: "success" });

            $.ajax({
                url: 'registrarfacilitadores.php',
                method: 'POST',
                data: {
                    particadd: JSON.stringify(ParticipantesAdicionadosvalor)
                },
        
                success: function(response) {
        
                    console.log("(4.2) Deu bom! AJAX está enviando os participantes");
                    console.log("Response:", response);   
                    setTimeout(function() {
                        console.log("Redirecionando..."); // Adicionado para depuração  
                    }, 1500);
                    
                    // Limpa a lista de participantes adicionados
                    ParticipantesAdicionadosvalor = [];
                    atualizarListaParticipantes();
        
                },
        
                error: function(error) {
                    console.error('Erro na solicitação AJAX:', error);
                }
            });
        }
  
}

function atualizarListaParticipantes() {
    itemList.innerHTML = ''; // Limpa a lista visualmente
}

///------------BOTÃO DE REGISTRAR EMAIL DENTRO DA MODAL------------------------------

var botaoemail = document.getElementById("registraremail");
botaoemail.addEventListener('click', gravaremail);

function gravaremail(){

    var caixadenome = document.getElementById("caixanome").value;
    var caixadeemail = document.getElementById("caixadeemail").value;
    var caixamatricula = document.getElementById("caixamatricula").value;
   
    if (caixadenome.trim() === "" || 
        caixadeemail.trim() === "" || 
        caixamatricula.trim() === "" )
            {
        
        Swal.fire({
            title: "Erro no registro",
            text: "Preencha todas as caixas do formulário",
            icon: "error"
          });

          console.log ("(X) Puxou a function da modal, mas não preencheu todas as informações")
          console.log ("Que bom, o seu nome é: " + caixadenome + " seu email é " + caixadeemail)
    } 
    
    else {

        Swal.fire({
            title: "Cadastrado com sucesso!",
            text: "Atualize a página e continue a operação",
            icon: "success"
          });

        console.log ("(3.1) As informações de email foram enviadas");

        if (caixadenome !=="" && caixadeemail !=="" && caixamatricula !=="") 

        $.ajax({
            url: 'registrarpessoas.php',
            method: 'POST',
            data: {
               caixaname: caixadenome,
               caixaemail: caixadeemail,
               caixamatricula: caixamatricula,
            },

            success: function (response) {
                console.log("(3.2) Deu bom! AJAX está enviando");
                console.log (caixamatricula + " " +caixadenome + " " + caixadeemail)
                console.log(response);

                
            },
            error: function (error) {
                // console.log('Erro na solicitação AJAX:', error);
            }
        });
    };

};   