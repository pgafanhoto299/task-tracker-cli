<?php

    declare(strict_types=1); // tipagem estrita
    const FICHEIRO = "tasks.json"; // nome do ficheiro
    $argv1 = isset($argv[1]) ? strtolower($argv[1]) : null; // se o argv1 estiver definido converta para minusculas
    $argv2 = $argv[2] ?? null; //se a esquerda não existir argv2 recebe null
    $argv3 = $argv[3] ?? null;

    // Verificar se o Id é valido e fazer a conversão
    function lerId(?string $id): int {
        if($id === null || !ctype_digit($id) || (int)$id < 1){
            throw new InvalidArgumentException("ID inválido: indica um numero posetivo.");
        }
        return (int)$id;
    }   

    //truncar o texto e verificar se é válido
    function lerTexto(?string $texto): string{
        $texto = trim($texto ?? '');
        if ($texto === ''){
            throw new InvalidArgumentException("O campo não pode ser vazio.");
        }
        return $texto;
    }
    try{    
        //Menu do Todo List
        switch($argv1) {
            case "add":
                add(lerTexto($argv2));
                break;
            case "update":
                actualizarNome(lerId($argv2), lerTexto($argv3));
                break;
            case "delete":
                apagar(lerId($argv2));
                break;
            case "mark-todo":
            case "mark-in-progress":
            case "mark-done":
                markAs(lerId($argv2), substr($argv1, 5));
                break;
            case "list-all":
                echo "Lista de todas as tarefas";
                listarTodasAsTarefas();
                break;
            case "list-done":
            case "list-in-progress":
                listTasks(mb_substr($argv1, 5));
                break;
            default:
                throw new InvalidArgumentException("Comando inválido");
            }
        }catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Erro: " . $e->getMessage() . PHP_EOL);
            exit(2);
        }catch(RuntimeException $e){
            fwrite(STDERR, "Erro: " . $e->getMessage() . PHP_EOL);
            exit(1);
        }
    

    // Ler a lista de tarefas usando o file_exist
    // file_get_contents e o json encode
    function readTasks(): ?array {
        if(!file_exists(FICHEIRO)){ return []; }

        $tarefas = file_get_contents(FICHEIRO);
        if ($tarefas === false){
            throw new RuntimeException("Não foi possivel ler" . FICHEIRO);
        }
        try{
            $listaDeTarefas = json_decode($tarefas, true, 512, JSON_THROW_ON_ERROR );
        }catch (JsonException $e){
            throw new RuntimeException("JSON inválido: " . $e->getMessage());
        }
        return is_array($listaDeTarefas) ?  $listaDeTarefas : [];
    }

    //Persistência dos dados com JSON
    function save(array $tarefas):void {
        
        try {    
            $json = json_encode(array_values($tarefas), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);//Remover a numeração das chaves            
        }catch(JsonException $e){
            throw new RuntimeException("Não foi possivel codificar as tarefas: " . $e->getMessage());
        }
        if(file_put_contents(FICHEIRO, $json, LOCK_EX) === false){
            throw new RuntimeException("Não foi possivel gravar" . FICHEIRO);
        }   
    }
    //Adicionar uma tarefa
    function add(string $novaTarefaNome): void{
        $tarefas = readTasks();
        $novaTarefa = [
            //Gerar ids sequenciais
            "id" => (!empty($tarefas) && is_array($tarefas)) ? max(array_column($tarefas, 'id')) + 1 : 1,
            "description" => $novaTarefaNome,
            "status" => "todo",
            "createdAt" => date('d/m/Y H:i:s'),
            "updatedAt" => date('d/m/Y H:i:s')
        ];
        $tarefas[] = $novaTarefa;
        save($tarefas);
        echo "$novaTarefaNome adicionado a Lista de Tarefas\n";
    }
    //Listar todas as tarefas
    function listarTodasAsTarefas(): void{
        $tarefas = readTasks();
        foreach ($tarefas as $tarefa){
            foreach($tarefa as  $chave => $valor){
                echo "$chave: $valor | ";
            }
            echo "\n";
        }
    }

    //Lista todas as tarefas
    function listTasks(?string $status):void{
        $tarefas = readTasks();
        foreach ($tarefas as $tarefa){
            if($tarefa['status'] === $status){    
                echo "id: ".$tarefa['id']. "|";
                echo "description: ".$tarefa['description']. "|";
                echo "status: ".$tarefa['status']. "|";
                echo "createdAt: ".$tarefa['createdAt']. "|";
                echo "updatedAt: ".$tarefa['updatedAt']. "|";
                echo "\n";}

        }
    }
    //Actualizar o nome
    function actualizarNome(int $str2, string $str3):void{
        $tarefas = readTasks();
        foreach($tarefas as $chave =>$tarefa){
            //Verificação de segurança: garante que o item é mesmo um
            // array antes de testar o id
            if(is_array($tarefa) && isset($tarefa['id']) && $tarefa['id'] === $str2){
                // Alterar o valor na lista principal
                $tarefas[$chave]['description'] = $str3;
                $tarefas[$chave]['updatedAt'] = date('d/m/Y H:i:s');
                save($tarefas);
                echo "Nome da tarefa $str2 actualizado!\n";
                return;
            }
        }            
            echo "Nome não encontrado\n";
    }

    function markAs(int $str2, string $str3):void{
        $tarefas = readTasks();
        foreach($tarefas as $chave => $tarefa){
            //Verificação de segurança: garante que o item é mesmo um
            // array antes de testar o id
            if(is_array($tarefa) && isset($tarefa['id']) && $tarefa['id'] === $str2){
                //Alterar o valor na lista principal
                $tarefas[$chave]['status'] = $str3;
                $tarefas[$chave]['updatedAt'] = date('d/m/Y H:i:s');
                save($tarefas);
                echo "Status da tarefa $str2 actualizado!\n";
                return;
            }
        }
        echo "Item não encontrado";
    }

    function apagar(int $str):void{
        $tarefas = readTasks();
        foreach($tarefas as $chave => $tarefa){
            //Verificação de segurança: garante que o item é mesmo um
            // array antes de testar o id
            if(is_array($tarefa) && isset($tarefa['id']) && $tarefa['id'] === $str){
                //Alterar o valor na lista principal
                unset($tarefas[$chave]);
                save($tarefas);
                echo "Tarefa Nº $str apagada.\n";
                return;
            }
        }
        echo "Tarefa $str não encontrada.\n";
    }
?>