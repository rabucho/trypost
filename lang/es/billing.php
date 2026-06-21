<?php

return [
    'title' => 'Facturación',

    'past_due_notice' => [
        'title' => 'Pago vencido',
        'description' => 'Actualiza tu método de pago para mantener tu suscripción activa.',
        'cta' => 'Actualizar pago',
    ],

    'annual_banner' => [
        'title' => 'Ahorra 2 meses con la facturación anual',
        'description' => 'Cambia al anual y obtén 2 meses gratis — paga menos cada mes.',
        'cta' => 'Cambiar a anual',
    ],

    'subscribe' => [
        'page_title' => 'Elige tu plan',
        'eyebrow' => 'Precios',
        'title' => 'Elige el plan ideal para ti',
        'description' => 'Elige el plan que te queda. Facturación mensual o anual.',
        'trial_info' => 'Prueba gratuita de :days días, luego se cobra automáticamente',
        'monthly' => 'Mensual',
        'yearly' => 'Anual',
        'per_month' => 'mensual',
        'per_year' => 'anual',
        'billed_monthly' => 'Facturado mensualmente',
        'billed_yearly' => 'Facturado anualmente',
        'features_included' => 'Qué incluye:',
        'everything_in' => 'Todo lo de :plan, más:',
        'save_months' => '2 meses gratis',
        'popular' => 'Más popular',
        'start_trial' => 'Comenzar prueba de :days días',
        'subscribe_cta' => 'Suscribirse',
        'prices' => [
            'workspace' => ['monthly' => '$12', 'yearly_per_month' => '$10', 'yearly' => '$120'],
        ],
        'features' => [
            'per_workspace_credits' => ':count créditos de IA por workspace/mes',
            'one_per_network' => 'Una cuenta por red social',
            'unlimited_members' => 'Miembros del equipo ilimitados',
            'all_platforms' => 'Publica en todas las plataformas compatibles',
        ],
        'credit_tooltips' => [
            'workspace' => 'Cada workspace incluye 2.500 créditos de IA al mes — alrededor de 160 imágenes o 280 textos generados con IA.',
        ],
    ],

    'plan' => [
        'title' => 'Plan',
        'description' => 'Gestiona tu plan de suscripción.',
        'label' => 'Plan',
        'workspaces' => '{1}:count workspace|[2,*]:count workspaces',
        'per_workspace' => 'por workspace',
        'price' => 'Precio',
        'month' => 'mes',
        'trial' => 'Prueba',
        'active' => 'Activo',
        'past_due' => 'Vencido',
        'cancelling' => 'Cancelando',
        'trial_ends' => 'La prueba termina en',
    ],

    'subscription' => [
        'title' => 'Suscripción',
        'description' => 'Gestiona tu método de pago, datos de facturación y suscripción.',
        'payment_method' => 'Método de pago',
        'no_payment_method' => 'Aún no hay método de pago registrado.',
        'expires_on' => 'Vence el :month/:year',
        'manage_label' => 'Suscripción',
        'manage_stripe' => 'Gestionar en Stripe',
    ],

    'invoices' => [
        'title' => 'Facturas',
        'description' => 'Descarga tus facturas anteriores.',
        'empty' => 'No se encontraron facturas',
        'paid' => 'Pagado',
    ],

    'flash' => [
        'plan_changed' => 'Ahora estás en el plan :plan.',
        'switched_to_yearly' => 'Ahora tienes facturación anual.',
        'cannot_manage' => 'Solo el propietario de la cuenta puede gestionar la facturación.',
        'credits_exhausted' => 'Sin créditos de IA — has usado tus :limit créditos mensuales. Mejora tu plan o espera hasta el próximo mes.',
        'subscription_required' => 'Se requiere una suscripción activa para usar las funciones de IA.',
    ],

    'processing' => [
        'page_title' => 'Procesando...',
        'title' => 'Procesando tu suscripción',
        'description' => 'Espera mientras configuramos tu cuenta. Solo tomará un momento.',
        'success_title' => '¡Todo listo!',
        'success_description' => 'Tu suscripción está activa. Redirigiendo a tus workspaces...',
        'cancelled_title' => 'Pago cancelado',
        'cancelled_description' => 'Tu pago fue cancelado. No se realizaron cargos.',
        'retry' => 'Intentar de nuevo',
    ],
];
