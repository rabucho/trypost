<?php

return [
    'title' => 'Faturamento',

    'past_due_notice' => [
        'title' => 'Pagamento em atraso',
        'description' => 'Atualize sua forma de pagamento para manter sua assinatura ativa.',
        'cta' => 'Atualizar pagamento',
    ],

    'annual_banner' => [
        'title' => 'Economize 2 meses com a cobrança anual',
        'description' => 'Mude para o anual e ganhe 2 meses grátis — pague menos por mês.',
        'cta' => 'Mudar para anual',
    ],

    'subscribe' => [
        'page_title' => 'Escolha seu plano',
        'eyebrow' => 'Preços',
        'title' => 'Escolha o plano ideal pra você',
        'description' => 'Escolha o plano que combina com você. Cobrança mensal ou anual.',
        'trial_info' => ':days dias grátis, depois cobrança automática',
        'monthly' => 'Mensal',
        'yearly' => 'Anual',
        'per_month' => 'mensal',
        'per_year' => 'anual',
        'billed_monthly' => 'Cobrança mensal',
        'billed_yearly' => 'Cobrança anual',
        'features_included' => 'O que está incluído:',
        'everything_in' => 'Tudo do :plan, mais:',
        'save_months' => '2 meses grátis',
        'popular' => 'Mais popular',
        'start_trial' => 'Iniciar teste de :days dias',
        'subscribe_cta' => 'Assinar',
        'prices' => [
            'workspace' => ['monthly' => 'R$ 60', 'yearly_per_month' => 'R$ 50', 'yearly' => 'R$ 600'],
        ],
        'features' => [
            'per_workspace_credits' => ':count créditos de IA por workspace/mês',
            'one_per_network' => 'Uma conta por rede social',
            'unlimited_members' => 'Membros da equipe ilimitados',
            'all_platforms' => 'Publique em todas as plataformas suportadas',
        ],
        'credit_tooltips' => [
            'workspace' => 'Cada workspace inclui 2.500 créditos de IA por mês — cerca de 160 imagens ou 280 textos gerados por IA.',
        ],
    ],

    'plan' => [
        'title' => 'Plano',
        'description' => 'Gerencie seu plano de assinatura.',
        'label' => 'Plano',
        'workspaces' => '{1}:count workspace|[2,*]:count workspaces',
        'per_workspace' => 'por workspace',
        'price' => 'Preço',
        'month' => 'mês',
        'trial' => 'Trial',
        'active' => 'Ativo',
        'past_due' => 'Vencido',
        'cancelling' => 'Cancelando',
        'trial_ends' => 'Teste termina em',
    ],

    'subscription' => [
        'title' => 'Assinatura',
        'description' => 'Gerencie seu método de pagamento, dados de cobrança e assinatura.',
        'payment_method' => 'Método de pagamento',
        'no_payment_method' => 'Nenhum método de pagamento cadastrado.',
        'expires_on' => 'Expira em :month/:year',
        'manage_label' => 'Assinatura',
        'manage_stripe' => 'Gerenciar no Stripe',
    ],

    'invoices' => [
        'title' => 'Faturas',
        'description' => 'Baixe suas faturas anteriores.',
        'empty' => 'Nenhuma fatura encontrada',
        'paid' => 'Pago',
    ],

    'flash' => [
        'plan_changed' => 'Você está agora no plano :plan.',
        'switched_to_yearly' => 'Você está agora na cobrança anual.',
        'cannot_manage' => 'Apenas o owner da conta pode gerenciar a cobrança.',
        'credits_exhausted' => 'Sem créditos de IA — você usou seus :limit créditos mensais. Faça upgrade do plano ou aguarde até o próximo mês.',
        'subscription_required' => 'É necessária uma assinatura ativa para usar os recursos de IA.',
    ],

    'processing' => [
        'page_title' => 'Processando...',
        'title' => 'Processando sua assinatura',
        'description' => 'Aguarde enquanto configuramos sua conta. Isso levará apenas um momento.',
        'success_title' => 'Tudo pronto!',
        'success_description' => 'Sua assinatura está ativa. Redirecionando para seus workspaces...',
        'cancelled_title' => 'Pagamento cancelado',
        'cancelled_description' => 'Seu pagamento foi cancelado. Nenhuma cobrança foi realizada.',
        'retry' => 'Tentar novamente',
    ],
];
