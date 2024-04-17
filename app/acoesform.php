<?php
namespace formulario;

class AcoesForm {

    private $pdo;
    public function __construct() {
        try {
            $dbhost = 'localhost';
            $dbname = 'atareu';
            $dbuser = 'root';
            $dbpass = '';

            // Conexão com o banco de dados usando PDO
            $this->pdo = new \PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function pegarlocais() {
        try {
            $sql = "SELECT locais FROM locais";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $resultados;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function selecionarFacilitadores() {
        try {
            $sql = "SELECT * FROM facilitadores";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $resultados;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function puxarId() {
        try {
            $sql = "SELECT id FROM assunto ORDER BY id DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $resultados;
        } catch (\PDOException $e) {
            throw $e;
        }
    } 

    public function pegarfacilitador() {
        try {
            $sql = "SELECT id, nome_facilitador, matricula FROM facilitadores ORDER BY nome_facilitador ASC ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $resultados;
        } catch (\PDOException $e) {
            throw $e;
        }
    } 
        
    public function obterUltimoRegistro() {
        try {
            $sql = "SELECT tema, hora_inicial, hora_termino, data_solicitada, objetivo, local 
                    FROM assunto 
                    ORDER BY data_registro DESC 
                    LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                return $row;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            throw $e;
        }
    }



    public function pegarUltimaAta() {
        session_start();
        try {
            $sql = "SELECT  tema, hora_inicial, hora_termino, data_solicitada, objetivo, local 
                    FROM assunto 
                    ORDER BY data_registro DESC 
                    LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                $_SESSION['conteudo'] = $row["tema"];
                $_SESSION['horainicio'] = substr($row["hora_inicial"], 0, 5);
                $_SESSION['horaterm'] = substr($row["hora_termino"], 0, 5);
                $_SESSION['data'] = substr($row["data_solicitada"], 0, 10);
                $_SESSION['objetivoSelecionado'] = $row["objetivo"];
                $_SESSION['local'] = $row["local"];

            } else {
                echo "Nenhum resultado encontrado";
            }
        } catch (\PDOException $e) {
            echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
        }
    }

    public function puxandoUltimosFacilitadores() {
        try {
            
            $sql1 = "SELECT id FROM assunto ORDER BY id DESC LIMIT 1";
            $stmt1 = $this->pdo->prepare($sql1);
            $stmt1->execute();
            $lastAtaId = $stmt1->fetchColumn();
    
            // Em seguida, usamos esse ID para obter os registros da tabela de associação
            $sql2 = "SELECT id_ata, facilitadores FROM ata_has_fac WHERE id_ata = ?";
            $stmt2 = $this->pdo->prepare($sql2);
            $stmt2->execute([$lastAtaId]);
            $resultadosAtaFacilitadores = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
    
            // Agora, usamos os IDs dos facilitadores para obter suas informações
            $facilitadores = [];

            foreach ($resultadosAtaFacilitadores as $resultado) {

                $facilitadorId = $resultado['facilitadores'];
                $sql3 = "SELECT id, matricula, nome_facilitador FROM facilitadores WHERE id = ?";
                $stmt3 = $this->pdo->prepare($sql3);
                $stmt3->execute([$facilitadorId]);
                $facilitadorInfo = $stmt3->fetch(\PDO::FETCH_ASSOC);

                if ($facilitadorInfo) {
                    $facilitadores[] = $facilitadorInfo;
                }
            }
    
            return $facilitadores;
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    public function puxandoUltimosParticipantes() {
    try {
        $sql1 = "SELECT id_ata FROM ata_has_fac ORDER BY id DESC";
        $stmt1 = $this->pdo->prepare($sql1);
        $stmt1->execute();
        $lastAtaId = $stmt1->fetchColumn();
    
        $sql2 = "SELECT id_ata, facilitadores FROM ata_has_fac WHERE id_ata = ?";
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute([$lastAtaId]);
        $resultadosAtaParticipantes = $stmt2->fetchAll(\PDO::FETCH_ASSOC);
    
        $participantesFacilitadores = [];
    
        foreach ($resultadosAtaParticipantes as $resultado) {
            $participanteId = $resultado['facilitadores'];
    
            $sql3 = "SELECT id, matricula, nome_facilitador FROM facilitadores WHERE id = ?";
            $stmt3 = $this->pdo->prepare($sql3);
            $stmt3->execute([$participanteId]);
            $participanteInfo = $stmt3->fetch(\PDO::FETCH_ASSOC);
    
            if ($participanteInfo) {
                $participantesFacilitadores[] = $participanteInfo;
            }
        }
    
        return $participantesFacilitadores;
    } catch (\PDOException $e) {

        throw $e;
    }}

    public function pegandoTudo(){



        $sql="SELECT 
        A.id,
        A.data_solicitada,
        A.objetivo,
        F.nome_facilitador as facilitador,
        A.tema,
        A.local,
        A.status
        
        FROM assunto as A
        
             INNER JOIN ata_has_fac as B
                ON A.id = B.id_ata
                
             INNER JOIN facilitadores as F
                ON B.facilitadores = F.id;";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                    return $row;
                } else {
                    return false;
                }
            
    }
    
    
       }
