<?php

    $ficheiro = "tasks.json";
    $argv[1] = strtolower($argv[1]);
    switch($argv[1]) {
        case "add":
            add($ficheiro, $argv[2]);
            break;
        case "update":
            echo "Tarefa atualizada";
            break;
        case "delete":
            echo "Tarefa deletada";
            break;
        case "todo":
            echo "Actualizando o status da tarefa para todo...";
            break;
        case "in-progress":
            echo "Actualizando o status da tarefa para in-progress...";
            break;
        case "done":
            echo "Actualizando o status da tarefa para done...\n";
            break;
        case "in-progress":
            echo "Tarefa em andamento";
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
            echo "Não há por fazer!";
    }

    // Ler a lista de tarefas usando o file_exist
    // fie_get_contents e o json encode
    function readTasks(string $ficheiro){
        if(!file_exists($ficheiro)){
            echo "Ficheiro inexistente";
            return [];
        }
        $tarefas = file_get_contents($ficheiro);
        return json_decode($tarefas, true) ? : [];
    }

    function save(string $ficheiro, array $tarefas){
        $jsonString = json_encode($tarefas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($ficheiro, $jsonString);
        echo "Task added successfully";
    }
    //Adicionar uma tarefa
    function add(string $ficheiro, string $novaTarefaNome){
        $tarefas = readTasks($ficheiro);
        //Gerar um id usando a função time();
        $novaTarefa = [
            "id" => time(),
            "description" => $novaTarefaNome,
            "status" => "Não iniciada",
            "createdAt" => date('d/m/Y H:i:s', time()),
            "updateAt" => date('d/m/Y H:i:s', time())
        ];
        $tarefas[] = $novaTarefa;
        save($ficheiro, $tarefas);
    }

    //Lista todas as tarefas
    function listarTodasAsTarefas($ficheiro){
        $tarefas = readTasks($ficheiro);
        foreach ($tarefas as $tarefas){
            foreach($tarefas as  $chave => $valor){
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
   
?>