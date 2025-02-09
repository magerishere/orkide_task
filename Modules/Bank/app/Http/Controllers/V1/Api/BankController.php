<?php

namespace Modules\Bank\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Bank\Exceptions\BankAccountBalanceNotEnoughException;
use Modules\Bank\Http\Requests\V1\Api\BankCardToCardRequest;
use Modules\Bank\Repository\Contracts\BankAccountCardRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankAccountRepositoryInterface;
use Modules\Bank\Repository\Contracts\BankRepositoryInterface;
use Modules\Transaction\Enums\TransactionStatus;
use Modules\Transaction\Repository\Contracts\TransactionRepositoryInterface;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bank::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bank::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('bank::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('bank::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function cardToCard(
        BankCardToCardRequest $request,
        User $user,
        BankAccountCardRepositoryInterface $bankAccountCardRepository,
        BankAccountRepositoryInterface $bankAccountRepository,
        BankRepositoryInterface $bankRepository,
        TransactionRepositoryInterface $transactionRepository,
    )
    {
        try {
            $bankAccount = $bankAccountRepository->freshQuery()->findBy('user_mobile',$user->mobile)->getModel();
            if(! $bankAccount->checkBalance(amount: $request->get('amount'))) {
                throw new BankAccountBalanceNotEnoughException();
            }
//            $bank = $bankRepository->findByPrefixCardNumber(prefix: $request->get('prefix_from_card_number'))->getModel();

            $fromCard = $bankAccountCardRepository->freshQuery()->findById(id: $request->get('from_card_number'))->getModel();
            if(! $fromCard->getKey()) {

                $fromCard = $bankAccountCardRepository->freshQuery()->create(
                    data: [
                        'number' => $request->get('from_card_number'),
                        'bank_account_number' => $bankAccount->number,
                    ]
                )->getModel();
            }

            $toCard = $bankAccountCardRepository->freshQuery()->findById(id: $request->get('to_card_number'))->getModel();

            $transactionRepository->freshQuery()->create([
                'ref_number' => $transactionRepository->newRefNumber(),
                'from_bank_account_card_number' => $fromCard->number,
                'to_bank_account_card_number' => $toCard->number,
                'amount' => $request->get('amount'),
            ]);

            $bankAccountRepository->decrementBalance(amount: $request->get('amount'));

            $transactionRepository->update(
                data: [
                    'status' => TransactionStatus::COMPLETED,
                ]
            );

             return apiResponse([],__('base::v1.successful_loaded'));
        } catch (\Exception $exception) {
            report($exception);
            return apiResponse([],$exception);
        }
    }
}
