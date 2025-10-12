# MarkTour — Aplicação PHP

Este repositório contém o MarkTour, uma aplicação PHP (desenvolvida para execução sob XAMPP) para gestão de empresas, alojamentos, passeios e reservas. O projeto inclui uma versão vendorizada do Google API PHP Client (v2.18.3, adaptada para PHP 8.3) em `lib/google-api-php-client-v2.18.3-PHP8.3` para permitir integração com Google OAuth sem exigir Composer no sistema.

## Sumário
- Visão geral
- Pré-requisitos
- Como executar localmente
- Configuração do Google OAuth
- Como evitar/solucionar o erro Intelephense P1009 (Undefined type `Google_Service_Oauth2`)
- Estrutura do projeto (resumo)
- Boas práticas e próximos passos

## Visão geral
MarkTour é uma aplicação monolítica em PHP que usa código procedural e orientado a objetos simples. A integração com Google Sign-In foi adicionada usando a biblioteca `google-api-php-client`, que foi incluída diretamente em `lib/` (vendored) para evitar a necessidade de instalar Composer globalmente.

## Pré-requisitos
- Windows (testado com XAMPP)
- XAMPP (Apache + PHP)
- PHP 8.x compatível (projeto adaptado para PHP 8.3)
- Navegador web

Recomenda-se usar o VS Code com a extensão Intelephense para análise estática. Se usar Intelephense, veja a seção dedicada para evitar falsos positivos P1009.

## Como executar localmente
1. Coloque o diretório do projeto em `c:\xampp\htdocs\marktour` (se ainda não estiver lá).
2. Inicie o Apache/MySQL via XAMPP.
3. Abra `http://localhost/marktour` no navegador.

Nota: O projeto espera um ficheiro de configuração do OAuth do Google em `config/client_secret.json` (veja a secção abaixo). Sem esse ficheiro, o endpoint de autenticação do Google lançará exceção.

## Configuração do Google OAuth
1. Crie um projeto no Google Cloud Console e configure uma OAuth 2.0 Client ID (tipo Web application).
2. Adicione `http://localhost/marktour/Controller/Auth/GoogleCallback.php` (ou o URL correto do callback) às URIs autorizadas de redirecionamento.
3. Baixe o ficheiro JSON (credentials) e coloque-o em `config/client_secret.json` na raiz do projeto.
4. Verifique que o caminho `config/client_secret.json` está acessível pelo PHP (permissões corretas).

## Usando a biblioteca Google vendorizada (sem Composer)
A biblioteca Google está incluída em `lib/google-api-php-client-v2.18.3-PHP8.3`. O projeto já contém um autoload vendor em `lib/.../vendor/autoload.php` e o controlador de autenticação (`Controller/Auth/GoogleAuthController.php`) foi atualizado para:
- Preferir `lib/.../vendor/autoload.php` se presente.
- Fornecer um autoloader fallback PSR-4 simples (somente para tornar a biblioteca utilizável sem Composer instalado globalmente).

Se desejar instalar dependências via Composer localmente, pode executar (num ambiente com Composer):

```bash
cd c:\xampp\htdocs\marktour\lib\google-api-php-client-v2.18.3-PHP8.3
composer install
```

Mas isso não é obrigatório: a versão vendorada já é utilizável.

## Intelephense P1009 (Undefined type `Google_Service_Oauth2`)
Problema:
- Intelephense pode reportar `Undefined type 'Google_Service_Oauth2' (P1009)` porque a biblioteca usa classes namespaced (`Google\Service\Oauth2`) e geração dinâmica de aliases legados (`class_alias`) que às vezes não são detectados pelo indexador.

Soluções aplicadas / recomendadas (rápidas):
1. O controlador `Controller/Auth/GoogleAuthController.php` já foi atualizado para usar as classes namespaced (`Google\Client` e `Google\Service\Oauth2`) e também contém uma pequena declaração condicional para informar o analisador sobre o nome legado:

```php
if (!class_exists('Google_Service_Oauth2')) {
    /** @noinspection PhpUndefinedClassInspection */
    class Google_Service_Oauth2 {}
}
```

2. Recarregue o VS Code para forçar a reindexação do Intelephense: Command Palette → "Developer: Reload Window".
3. Se o problema persistir, adicione um require condicional para `stubs/intelephense_google_service_stubs.php` em um arquivo carregado pelo projeto (por exemplo `index.php`) — eu posso aplicar isso automaticamente se preferir.
4. A correção definitiva é substituir usos legados `Google_Service_*` por `\Google\Service\*` em todo o projeto.

## Estrutura (resumo)
Aqui estão os diretórios principais do projeto (resumido):

- `Controller/` — controladores HTTP por domínio (Auth, Empresa, Utilizador, Admin, ...)
- `Model/` — modelos simples (Empresa, Usuario, Alojamento, Evento, ...)
- `View/` — templates / páginas do frontend
- `Helpers/` — funções utilitárias (sessão, criptografia, etc.)
- `lib/google-api-php-client-v2.18.3-PHP8.3/` — biblioteca Google vendorizada (includes `vendor/` com autoload)
- `stubs/` — pequenos stubs para ajudar analisadores (ex.: Intelephense)
- `uploads/` — uploads de ficheiros (imagens, documentos)
- `Conexao/` — ficheiro `conector.php` e `bd.sql`

## Boas práticas e próximos passos
- Ideal: migrar o projecto para usar Composer corretamente (manter `vendor/` v1) e referenciar o `vendor/autoload.php` no bootstrap principal (ex.: `index.php`). Isso simplificaria autoload e reduziria stubs.
- Refatoração: substituir referências legadas `Google_Service_*` por nomes namespaced `\Google\Service\*` em todo o código para evitar problemas futuros com analisadores.
- Segurança: não comitar credenciais ou ficheiros sensíveis no repositório (ver `config/client_secret.json`).

## Contato
Para ajuda adicional, diga-me o que deseja que eu faça agora: (1) adicionar um require condicional do stub em `index.php`, (2) executar uma substituição automática de referências legadas por classes namespaced, ou (3) criar documentação adicional (diagrama, scripts de dev, etc.).
