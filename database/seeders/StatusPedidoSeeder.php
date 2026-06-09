<?php

namespace Database\Seeders;

use App\Models\StatusPedido;
use Illuminate\Database\Seeder;

class StatusPedidoSeeder extends Seeder
{
    public function run()
    {
        $statusList = [
            [
                'nome_status' => 'Aguardando Aprovação',
                'descricao'   => 'Pedido criado, pendente de análise do administrador',
            ],
            [
                'nome_status' => 'Aprovado',
                'descricao'   => 'Pedido aprovado, aguardando separação dos produtos',
            ],
            [
                'nome_status' => 'Em Separação',
                'descricao'   => 'Produtos estão sendo preparados para envio',
            ],
            [
                'nome_status' => 'Enviado',
                'descricao'   => 'Pedido despachado para transporte',
            ],
            [
                'nome_status' => 'Entregue',
                'descricao'   => 'Pedido entregue ao destino com sucesso',
            ],
            [
                'nome_status' => 'Cancelado',
                'descricao'   => 'Pedido cancelado antes do envio',
            ],
            [
                'nome_status' => 'Recusado',
                'descricao'   => 'Pedido rejeitado pelo administrador',
            ],
        ];

        foreach ($statusList as $status) {
            StatusPedido::firstOrCreate(
                ['nome_status' => $status['nome_status']],
                [
                    'descricao'  => $status['descricao'],
                    'status'     => 1,
                    'criado_por' => 1,
                ]
            );
        }
    }
}
