<?php

declare(strict_types=1);

namespace App\Http\Requests\App\Onboarding;

use App\Enums\User\Goal;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOnboardingGoalsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'goals' => ['required', 'array', 'min:1'],
            'goals.*' => [Rule::enum(Goal::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $goals = (array) $this->input('goals', []);

            if (in_array(Goal::JustExploring->value, $goals, true) && count($goals) > 1) {
                $validator->errors()->add('goals', __('onboarding.goals_exclusive'));
            }
        });
    }
}
