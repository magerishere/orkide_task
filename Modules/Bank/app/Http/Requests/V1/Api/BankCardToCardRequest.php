<?php

namespace Modules\Bank\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Bank\Models\Bank;
use Modules\Bank\Models\BankAccountCard;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankRepositoryInterface;

class BankCardToCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(BankRepositoryInterface $bankRepository, BankAccountCardRepositoryInterface $bankAccountCardRepository): array
    {
        $bankTable = $bankRepository->getModel()->getTable();
        $bankAccountCardTable = $bankAccountCardRepository->getModel()->getTable();
        return [
            'prefix_from_card_number' => ['required', Rule::exists($bankTable, 'prefix_card_number')],
            'prefix_to_card_number' => ['required', Rule::exists($bankTable, 'prefix_card_number')],
            'from_card_number' => ['required', 'digits:16', Rule::exists($bankAccountCardTable, 'number')],
            'to_card_number' => ['required', 'digits:16', Rule::exists($bankAccountCardTable, 'number'), 'different:from_card_number'],
            'amount' => ['required', 'integer', 'min:1000', 'max:50000000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $fromCardNumber = $this->get('from_card_number');
        $fromCardNumber = str($fromCardNumber)->remove('-')->toString();
        $fromCardNumber = faToEn($fromCardNumber);

        $toCardNumber = $this->get('to_card_number');
        $toCardNumber = str($toCardNumber)->remove('-')->toString();
        $toCardNumber = faToEn($toCardNumber);

        $amount = $this->get('amount');
        $amount = faToEn($amount);

        $this->merge([
            'prefix_from_card_number' => str($fromCardNumber)->take(6)->toString(),
            'prefix_to_card_number' => str($toCardNumber)->take(6)->toString(),
            'from_card_number' => $fromCardNumber,
            'to_card_number' => $toCardNumber,
            'amount' => $amount
        ]);
    }
}
