# Damas Way

Sistema de solicitação de produtos entre filiais — Rede Damas Educacional.

## Stack

- **Backend:** Laravel 8 (PHP 7.4+)
- **Frontend:** Bootstrap 5.3, jQuery 3.7.1, DataTables 1.13.8, Select2 4.1
- **Banco:** MySQL
- **Icons:** Bootstrap Icons 1.11.3
- **Font:** Inter (Google Fonts)
- **Integração:** API TOTVS RM (sync de coligadas e filiais)

## Requisitos

- PHP >= 7.4
- Composer
- MySQL

## Instalação

```bash
# Clonar o repositório
git clone <repo-url> && cd Damas-Way

# Instalar dependências PHP
composer install

# Configurar ambiente
cp .env.example .env
php artisan key:generate

# Configurar banco de dados no .env
# Configurar integração TOTVS no .env:
#   TOTVS_API_BASE_URL=
#   TOTVS_API_USER=
#   TOTVS_API_PASS=

# Rodar migrations
php artisan migrate --seed
```

## Estrutura de Pastas

```
app/
├── Console/Commands/     # Comando sync:totvs
├── Http/Controllers/     # Controllers do sistema
│   ├── Traits/           # TogglesStatus (trait compartilhada)
│   └── Middleware/       # CheckNivel (controle de acesso por nível)
├── Models/               # Eloquent Models
├── Providers/            # Service Providers
└── Services/             # TotvsRmService (integração TOTVS RM)

public/
├── css/custom.css        # Estilos globais do sistema
├── img/                  # Logos e assets estáticos
└── js/
    ├── shared/           # datatable-config.js (idioma pt-BR + plugins)
    ├── coligadas/        # JS específico da página
    ├── filiais/
    ├── niveis/
    ├── produtos/
    ├── categorias/
    ├── transportadoras/
    └── usuarios/

resources/views/
├── components/           # Blade components (modal, modal-confirmacao)
├── layouts/              # Layout principal + partials (sidebar, header)
├── auth/                 # Login, forgot/reset password
├── perfil/               # Página de perfil do usuário
├── produtos/             # Catálogo de produtos (cards)
├── categorias/           # Categorias de produto
├── coligadas/
├── filiais/
├── niveis/
├── transportadoras/
├── usuarios/
└── errors/               # Páginas de erro (403)
```

## Níveis de Acesso

| Nível | Acesso |
|-------|--------|
| Super Administrador | Tudo — todas as filiais + TOTVS sync + coligadas/filiais/níveis |
| Administrador | Usuários, Produtos, Categorias, Transportadoras (scoped por filial) |
| Operador | Dashboard + Perfil (futuro: pedidos) |

## Estrutura do Banco

| Tabela | Descrição |
|--------|-----------|
| usuarios | Usuários do sistema |
| niveis | Níveis de acesso |
| coligadas | Coligadas RM (sincronizadas via API) |
| filiais | Filiais vinculadas a coligadas (sincronizadas via API) |
| usuario_filial | Vínculo usuário ↔ filial |
| categorias | Categorias de produtos |
| produtos | Catálogo de produtos |
| produto_img | Imagens dos produtos |
| pedidos | Pedidos de solicitação |
| item_pedido | Itens do pedido |
| endereco_pedido | Endereço de entrega |
| status_pedido | Status disponíveis |
| historico_status_pedido | Log de mudança de status |
| ocorrencias | Ocorrências em pedidos |
| ocorrencia_img | Imagens das ocorrências |
| transportadoras | Transportadoras |

## Sincronização TOTVS RM

O sistema sincroniza coligadas e filiais com o TOTVS RM via REST API.

```bash
# Sincronizar manualmente
php artisan sync:totvs

# Apenas coligadas ou filiais
php artisan sync:totvs --only=coligadas
php artisan sync:totvs --only=filiais
```

A sincronização também roda automaticamente via scheduler (diariamente às 02:00).

## Executar

```bash
php artisan serve
# Ou para acessar pela rede:
php artisan serve --host=0.0.0.0 --port=8000
```

Acesse: http://localhost:8000
