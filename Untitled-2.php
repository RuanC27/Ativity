<?php

class Objeto {
    public $id;
    public $nome;
    public $descricao;

    public function __construct($id, $nome, $descricao) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
    }
}

class ObjetoRepository {
    private $objetos = [];
    private $idCounter = 1;

    public function adicionar($nome, $descricao) {
        $objeto = new Objeto($this->idCounter++, $nome, $descricao);
        $this->objetos[] = $objeto;
        return $objeto;
    }

    public function listar() {
        return $this->objetos;
    }

    public function alterar($id, $nome, $descricao) {
        foreach ($this->objetos as $objeto) {
            if ($objeto->id == $id) {
                $objeto->nome = $nome;
                $objeto->descricao = $descricao;
                return $objeto;
            }
        }
        return null;
    }

    public function apagar($id) {
        foreach ($this->objetos as $key => $objeto) {
            if ($objeto->id == $id) {
                unset($this->objetos[$key]);
                return true;
            }
        }
        return false;
    }

    public function procurarPorId($id) {
        foreach ($this->objetos as $objeto) {
            if ($objeto->id == $id) {
                return $objeto;
            }
        }
        return null;
    }
}

class Administrador {
    public $id;
    public $usuario;
    public $senha;

    public function __construct($id, $usuario, $senha) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->senha = password_hash($senha, PASSWORD_BCRYPT); 
    }
}

class AdministradorRepository {
    private $administradores = [];
    private $idCounter = 1;

    public function adicionar($usuario, $senha) {
        $admin = new Administrador($this->idCounter++, $usuario, $senha);
        $this->administradores[] = $admin;
        return $admin;
    }

    public function listar() {
        return $this->administradores;
    }

    public function alterar($id, $usuario, $senha) {
        foreach ($this->administradores as $admin) {
            if ($admin->id == $id) {
                $admin->usuario = $usuario;
                $admin->senha = password_hash($senha, PASSWORD_BCRYPT); 
                return $admin;
            }
        }
        return null;
    }

    public function apagar($id) {
        foreach ($this->administradores as $key => $admin) {
            if ($admin->id == $id) {
                unset($this->administradores[$key]);
                return true;
            }
        }
        return false;
    }

    public function autenticar($usuario, $senha) {
        foreach ($this->administradores as $admin) {
            if ($admin->usuario == $usuario && password_verify($senha, $admin->senha)) {
                return true;
            }
        }
        return false;
    }
}

$repoObjetos = new ObjetoRepository();
$repoAdministradores = new AdministradorRepository();

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));

$endpoint = $uri[1] ?? null;
$id = $uri[2] ?? null;

switch ($endpoint) {
    case 'objetos':
        if ($method == 'GET') {
            echo json_encode($repoObjetos->listar());
        } elseif ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $nome = $data['nome'] ?? null;
            $descricao = $data['descricao'] ?? null;
            $objeto = $repoObjetos->adicionar($nome, $descricao);
            echo json_encode($objeto);
        } elseif ($method == 'PUT' && $id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $nome = $data['nome'] ?? null;
            $descricao = $data['descricao'] ?? null;
            $objeto = $repoObjetos->alterar($id, $nome, $descricao);
            echo json_encode($objeto ? $objeto : ['message' => 'Objeto não encontrado']);
        } elseif ($method == 'DELETE' && $id) {
            $result = $repoObjetos->apagar($id);
            echo json_encode(['message' => $result ? 'Objeto apagado com sucesso' : 'Objeto não encontrado']);
        }
        break;

    case 'administradores':
        if ($method == 'GET') {
            echo json_encode($repoAdministradores->listar());
        } elseif ($method == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $usuario = $data['usuario'] ?? null;
            $senha = $data['senha'] ?? null;
            $admin = $repoAdministradores->adicionar($usuario, $senha);
            echo json_encode($admin);
        } elseif ($method == 'PUT' && $id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $usuario = $data['usuario'] ?? null;
            $senha = $data['senha'] ?? null;
            $admin = $repoAdministradores->alterar($id, $usuario, $senha);
            echo json_encode($admin ? $admin : ['message' => 'Administrador não encontrado']);
        } elseif ($method == 'DELETE' && $id) {
            $result = $repoAdministradores->apagar($id);
            echo json_encode(['message' => $result ? 'Administrador apagado com sucesso' : 'Administrador não encontrado']);
        }
        break;

    default:
        echo json_encode(['message' => 'Endpoint não encontrado']);
        break;
}

?>
