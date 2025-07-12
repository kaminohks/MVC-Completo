<?php
require_once 'utils/ConexaoBanco.php';
require_once 'utils/Util.php';

class Creator {
    private PDO $conexao;
    private string $servidor;
    private string $banco;
    private string $usuario;
    private string $senha;
    private array $tabelas = [];

    public function __construct(array $dados) {
        $this->servidor = $dados['servidor'];
        $this->banco = $dados['banco'];
        $this->usuario = $dados['usuario'];
        $this->senha = $dados['senha'];
    }

    public function executar(): void {
        $this->prepararDiretorios();
        $this->conectar();
        $this->carregarTabelas();
        $this->gerarArquivos();
        Util::compactarProjeto();
    }

    private function prepararDiretorios(): void {
        $pastas = ["sistema", "sistema/model", "sistema/control", "sistema/view", "sistema/dao", "sistema/css"];
        foreach ($pastas as $pasta) {
            if (!is_dir($pasta)) mkdir($pasta, 0777, true);
        }
    }

    private function conectar(): void {
        $this->conexao = ConexaoBanco::conectarComBanco($this->servidor, $this->banco, $this->usuario, $this->senha);
    }

    private function carregarTabelas(): void {
        $stmt = $this->conexao->query("SHOW TABLES");
        $this->tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function gerarArquivos(): void {
        // aqui entraria a lógica de gerar models, DAO, controladores e views
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $creator = new Creator($_POST);
    $creator->executar();
    header("Location: index.php?msg=2");
    exit;
}
