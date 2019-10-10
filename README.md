# Lasse Project Manager
### Introdução
O Laboratório de Automação e Simulação de Sistemas (LASSE) tem como propósito tornar a Itaipu Binacional cada vez mais autossuficiente, realizando simulações de sistemas elétricos e atualizações de automação de dispositivos na Usina. O laboratório também conta com a execução de projetos em Pesquisa, Desenvolvimento e Inovação realizados no Parque Tecnológico de Itaipu (PTI). Ao decorrer de seus anos de existência, a empresa ganhou mais visibilidade, novos funcionários, assim como novos projetos buscando auxilio em sistemas de gerenciamento de projetos como Redmine, porém esses sistemas tem a intenção de ser genéricos, implementando varias funcionalidades que nunca serão usadas pelos funcionários ou não apresentando uma funcionalidade necessária, o que causa insatisfação dos funcionários que utilizam o sistema.

Sendo assim, viu-se a necessidade da criação de uma aplicação web para gerenciamento de projetos, que iria ser mais especifico as nessidades dos LASSE, auxiliando os funcionários a controlar tempo de trabalho e gastos em projetos de uma maneira facil de entender, além de facilitar o trabalho da gerencia da empresa centralizando todas as ações dos funcionários em um único sistema, sendo assim, foi criado o software que foi chamado de Tracking Projects, porém esse sistema possuia dependência com o software Redmine, necessitando assim da criação de um softaware independente e com novas funcionalidades.

Outro problema encontrado pelos funcionarios é que para efetuar a requisição de compras, viagens, novos projetos, dentre outras ações, é necessário um processo burocrático cada vez mais presente no dia a dia, onde os mesmos necessitam preencher formulários em arquivos de texto no formato Word ou Libre Office, que em muitas vezes possuem campos que já foram preenchidos anteriormente em outros arquivos. 

Para resolver esse problema o sistema deve disponibilizar uma funcionalidade que irá receber um o formulário em arquivo de texto e exibirá o mesmo no navegador permitindo preencher esse formulário com dados cadastrados anteriormente e por fim gerar um novo documento de texto com os campos agora preenchidos.
### Objetivo Geral
 Este projeto tem como finalidade resolver o problema encontrado no LASSE, desenvolvendo um sistema Web de gerenciamento de projetos direcionado especificamente para os funcionários da empresa, sendo capaz de atender as necessidades da forma mais automática e ágil possível.
 
### Objetivos Específicos
- Entrevistar os funcionários do LASSE identificado suas necessidades, para assim, gerar os requisitos do sistema.

- Elaborar e criar banco de dados para o sistema.

- Desenvolver CRUD para  projetos, usuários, tarefas, atividades, viagens, compras e centro de custos

- Implementar cálculo do gasto total dos projetos com base no tempo de trabalho dos funcionários, viagens realizadas e compras feitas.

- Desenvolver geração de graficos de atividade dos funcionários e gastos em projetos para melhorar a compreenção dos usuários.

- Implementar funcionalidade de geração de formulário odt com base em formulário html gerado pelo sistema ou por outro formulário odt.

- Implementar acessibilidade para usuários com deficiencias visuais.

### Escopo
Inicialmente o sistema deve prever uma hierarquia de acesso em que o usuário denominado adiministrador terá acesso a todos os dados do sistema porém não poderá criar projetos nem alterar os existentes podendo apenas fazer comentários, sendo necessario uma senha única do sistema para cadastrar um novo administrador, enquanto o usuário denominado funcionário poderá acessar apenas os dados inseridos por ele ou compartilados com o mesmo além de poder criar projetos, tarefas e atividades dentro desses projetos. Ambos os usuários deverão mater seus cadastros com os seguintes dados: nome, cpf, rg, data de emissão do rg, data de nascimento, tipo de usuário, valor da hora de trabalho, formação, atuação, e-mail, login e senha, além disso os funcionários poderão criar em seus perfis atividades não planejadas que são imprevistos ocorridos durante horário de trabalho como atestados médicos ou atrasos. Os usuários deverão acessar o sistema através de um login.
 
Um funcionário poderá criar projetos mantendo os seguintes dados: data de inicio,data de finalização, funcionários, nome, total gasto, nome do convenio, numero do convenio, fonte de recurso, número centro de custo e descrição. Um funcionário também poderá inserir outros funcionários nos projetos criados por ele, permitindo assim a alteração de dados e a criação de tarefas e atividades dentro deste projeto. 	Os funcionários de um projeto poderão criar tarefas dentro desses projetos contendo os seguintes dados: data de inicio, estado(concluida, trabalhando, aguardando), data prevista para conclusão, nome e descrição. Uma tarefa só poderá ser criada caso sua data de inicio e conclusão estejam dentro do intervalo de duração do projeto. 

Dentro de uma tarefa o funcionário poderá inserir atividades planejadas mantendo os seguintes dados: nome, comentário, tempo investido, data da realização, tipo e total gasto ou viagens, mantendo os seguintes dados:  origem, destino, meta, data ida, data volta, justificativa, observações, passagem, veiculo, data entrada hospedagem, horario entrada hospedagem, data saida hospedagem, horario saida hospedagem e total gasto ou compras mantendo os seguintes dados: total gasto e produtos comprados e proposito da compra.Uma atividade só poderá ser criada caso sua data de realização esteja dentro do intervalo de duração da tarefa.

No perfil de um funcionário um grafico de colunas com o tempo gasto em cada projeto deve ser exibido podendo ser das atividades de um único dia ou de um mês inteiro.

No perfil do adiministrador poderá ser visualizado todos os projetos em uma dashboard dando acesso a graficos das atividades dos funcionarios ou gastos do projeto selecinado, além disso o administrador poderá visualizar os graficos de atividade de cada funcionário e os graficos de gastos em relação a todos os projetos.

Ao final da inserção de uma viagem ou de uma compra o funcionário poderá gerar um documento odt com os dados cadastrados utilizando um modelo predefinido ou fornecido pelo mesmo.

Todos os usuários terão acesso a uma funcionalidade em que o sistema deve receber um formulário em formato odt e converter para html exibindo para o usuario e identificando os campos, permitindo que o usuário preencha esses campos. Durante o preenchimento o sistema deve disponibilizar os dados de um projeto selecionado e do usuário, autopreenchendo campos com nomes parecidos com informações já cadastradas no banco de dados. Por fim, o sistema deve disponibilizar para download um arquivo odt com base formulário agora preenchido.
