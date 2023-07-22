# Estudo de caso FFMPEG & Laravel

Neste estudo de caso, foi desenvolvida uma aplicação web para testes de performance e resultados de conversão de vídeo.

## Tecnologias utilizadas

- Laravel 10
- Docker
- FFmpeg
- MySQL

## Sobre a aplicação
A aplicação é bem simples, conta com duas telas somente. A tela inicial conta com um campo de arquivo para inserir um vídeo, além da listagem dos vídeos previamente carregados. 
A segunda tela temos a tela de benchmarking, onde existem opções de conversão, dado o vídeo, ele também traz os resultados anteriores e dá a opção de exportar os dados para csv
 <div style="display:flex;justify-content:center;margin-bottom: 20px;align-items: center;">
        <img src="screenshots/tela1.png" alt="Tela do sistema que mostra um fundo escuro, o primeiro item é um título escrito Estudo de caso Laravel FFMPEG, abaixo um campo de formulário para envio de um vídeo e abaixo a listagem dos vídeos já enviados, com as opções, testar e excluir vídeo." title="Tela principal" width="100%"/>
</div>

<div style="display: flex;justify-content: center;margin-bottom: 20px;align-items: center">
        <img src="screenshots/tela2.png" alt="A tela tem um fundo escuro com fontes brancas, exibe-se informações sobre o vídeo escolhido, opçoes de conversão de formatos, a imagem mostra X264, opçoes de qualidade em 1080p, e por fim o bitrate, marcado em 1000, logo abaixo, temos a exibição dos itens já convertidos, o tempo gasto, e quais as configurações resultantes da conversão/compressão" title="Pagina Benchmark" width="100%"/>
</div>
