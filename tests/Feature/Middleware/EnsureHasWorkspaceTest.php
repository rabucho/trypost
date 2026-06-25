<?php

declare(strict_types=1);

use App\Models\User;

test('connect routes redirect to workspace creation when there is no current workspace', function () {
    $user = User::factory()->create(['current_workspace_id' => null]);

    $this->actingAs($user)
        ->get(route('app.social.x.connect'))
        ->assertRedirect(route('app.workspaces.create'));
});

test('connect routes require a workspace even in self-hosted mode', function () {
    config()->set('trypost.self_hosted', true);

    $user = User::factory()->create(['current_workspace_id' => null]);

    $this->actingAs($user)
        ->get(route('app.social.x.connect'))
        ->assertRedirect(route('app.workspaces.create'));
});
