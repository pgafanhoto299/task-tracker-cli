<?php

    $ficheiro = "tasks.json";
    $argv[1] = strtolower($argv[1]);

    //Menu do Todo List
    switch($argv[1]) {
        case "add":
            add($ficheiro, $argv[2]);
            break;
        case "update":
            echo "Actualizando tarefa...\n";
            actualizarNome($ficheiro, $argv[1], $argv[2], $argv[3]);
            break;
        case "delete":
            echo "Deletando a tarefa...";
            apagar($ficheiro, $argv[2]);
            break;
        case "mark-todo":
            echo "Actualizando o status da tarefa para todo...";
            markAs($ficheiro, $argv[1], $argv[2], "todo");
            break;
        case "mark-in-progress":
            echo "Actualizando o status da tarefa para in-progress...";
            markAs($ficheiro, $argv[1], $argv[2], "in-progress");
            break;
        case "mark-done":
            echo "Actualizando o status da tarefa para done...\n";
            markAs($ficheiro, $argv[1], $argv[2], "done");
            break;
        case "list-all":
            echo "Lista de todas as tarefas";
            listarTodasAsTarefas($ficheiro);
            break;
        case "list-done":
            echo "Lista das tarefas realizadas";
            listarTodasAsConcluidas($ficheiro);
            break;
        case "list-in-progress":
            echo "Lista de tarefas em andamento:\n";
            listarTodasEmProgresso($ficheiro);
            break;
        default:
            echo "Não há nada por fazer!";
    }

    // Ler a lista de tarefas usando o file_exist
    // file_get_contents e o json encode
    function readTasks(string $ficheiro){
        if(!file_exists($ficheiro)){
            echo "Ficheiro inexistente";
            return [];
        }
        $tarefas = file_get_contents($ficheiro);
        return json_decode($tarefas, true) ? : [];
    }
    //Persistência dos dados com JSON
    function save(string $ficheiro, array $tarefas){
        //Remover a numeração das chaves
        $tarefasLimpas = array_values($tarefas);
        $jsonString = json_encode($tarefasLimpas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($ficheiro, $jsonString);
    }
    //Adicionar uma tarefa
    function add(string $ficheiro, string $novaTarefaNome){
        $tarefas = readTasks($ficheiro);
        $novaTarefa = [
            "id" => time(),
            "description" => $novaTarefaNome,
            "status" => "todo",
            //Gerar um id usando a função time();
            "createdAt" => date('d/m/Y H:i:s', time()),
            "updateAt" => date('d/m/Y H:i:s', time())
        ];
        $tarefas[] = $novaTarefa;
        save($ficheiro, $tarefas);
    }
    //Listar todas as tarefas
    function listarTodasAsTarefas($ficheiro){
        $tarefas = readTasks($ficheiro);
        foreach ($tarefas as $tarefa){
            foreach($tarefa as  $chave => $valor){
                echo "$chave: $valor | ";
            }
            echo "\n";
        }
    }

    //Lista todas as tarefas
    function listarTodasAsConcluidas($ficheiro){
        $tarefas = readTasks($ficheiro);
        foreach ($tarefas as $tarefas){
            if($tarefas['status'] == "done"){    
                echo "id: ".$tarefas['id']. "|";
                echo "description: ".$tarefas['description']. "|";
                echo "status: ".$tarefas['status']. "|";
                echo "createdAt: ".$tarefas['createdAt']. "|";
                echo "updateAt: ".$tarefas['updateAt']. "|";
                echo "\n";}

        }
    }

    function listarTodasEmProgresso($ficheiro){
        $tarefas = readTasks($ficheiro);
        foreach ($tarefas as $tarefas){
            if($tarefas['status'] == "in-progress"){    
                echo "id: ".$tarefas['id']. "|";
                echo "description: ".$tarefas['description']. "|";
                echo "status: ".$tarefas['status']. "|";
                echo "createdAt: ".$tarefas['createdAt']. "|";
                echo "updateAt: ".$tarefas['updateAt']. "|";
                echo "\n";
            }

        }
    }
   
    //Actualizar o nome
    function actualizarNome(string $ficheiro, $str,  $str2, $str3){
        $tarefas = readTasks($ficheiro);
        $encontrou = 0;
        foreach($tarefas as $chave =>$tarefa){
            //Verificação de segurança: garante que o item é mesmo um
            // array antes de testar o id
            if(is_array($tarefa) && isset($tarefa['id']) && $tarefa['id'] == $str2){
                // Alterar o valor na lista principal
                $tarefas[$chave]['description'] = $str3;
                $tarefas[$chave]['updateAt'] = date('d/m/Y H:i:s', time());
                echo "Nome actualizado!\n";
                $encontrou = 1;
                break;
            }
        }

        if($encontrou == 1){
            save($ficheiro, $tarefas);
        }else{
            echo "Nome não encontrado\n";
        }
    }

    function markAs(string $ficheiro, $str, $str2, $str3){
        $tarefas = readTasks($ficheiro);
        $encontrou = 0;
        foreach($tarefas as $chave => $tarefa){
            //Verificação de segurança: garante que o item é mesmo um
            // array antes de testar o id
            if(is_array($tarefa) && isset($tarefa['id']) && $tarefa['id'] == $str2){
                //Alterar o valor na lista principal
                $tarefas[$chave]['status'] = $str3;
                $tarefas[$chave]['updateAt'] = date('d/m/Y H:i:s', time());
                echo "Status actualizado!\n";
                $encontrou = 1;
                break;
            }
        }

        if($encontrou == 1){
            save($ficheiro, $tarefas);
        }else{
            echo "Item não encontrado";
        }
    }

    function apagar($ficheiro, $str){
        $tarefas = readTasks($ficheiro);
        $encontrou = 0;
        foreach($tarefas as $chave => $tarefa){
            //Verificação de segurança: garante que o item é mesmo um
            // array antes de testar o id
            if(is_array($tarefa) && isset($tarefa['id']) && $tarefa['id'] == $str){
                //Alterar o valor na lista principal
                unset($tarefas[$chave]);
        
            }
        }
        save($ficheiro, $tarefas);
    }
?>