# Gerenciador de Tarefas na CLI
Aplicativo de linha de comando (CLI) para acompanhar suas tarefas e gerenciar sua lista de afazeres.
# Características
* **Adicionar, atualizar e excluir tarefas**
* __Marcar uma tarefa como em andamento ou concluída.__
* **Listar todas as tarefas**
* __Listar todas as tarefas que foram realizadas.__
* __Listar todas as tarefas que não foram concluídas.__
* __Listar todas as tarefas que estão em andamento.__
# Tecnologias Utilizadas
* **Linguagem de Programação**: PHP
* **Persistência dos Dados**: JSON File
# Instalação
1. Clonar este repositório
```
https://github.com/pgafanhoto299/task-tracker-cli.git
cd task-tracker-cli
```
# Uso
Execute a aplicação usando o seguinte comando:
```
php index.php
```
### 1. Adicionar tarefa
```
php index.php add "Tua Tarefa"
```
### 2. Alterar a descrição da tarefa
```
php index.php update <TASK_ID> <DESCRIPTION>
```
### 3. Apagar tarefa
```
php index.php delete <TASK_ID>
```
### 4. Actualizar estado da tarefa(todo, done ou in-progress)
* Marcar tarefa como por fazer
```
php index.php mark-todo <TASK_ID>
```
* Marcar tarefa como concluída
```
php index.php mark-done <TASK_ID>
```
* Marcar tarefa como em progresso
```
php index.php mark-in-progress <TASK_ID>
```
### 5. Listar todas as tarefas
```
php index.php list-all
```
### 6. Listar por tarefas por estado
* Listar tarefas concluídas
```
php index.php list-done
```
* Listar tarefas em progresso
```
php index.php list-in-progress
```

# Conclusão
O Gerenciador de Tarefas task-tracker-cli oferece uma interface simples para gerenciar tarefas diretamente da linha de comando. Para mais detalhes sobre o projeto, confira o [Projecto Task Tracker no Roadmap.sh](https://roadmap.sh/projects/task-tracker)
