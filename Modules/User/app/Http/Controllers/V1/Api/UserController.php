<?php

namespace Modules\User\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Bank\Models\BankAccountCard;
use Modules\Transaction\Repository\Contracts\TransactionRepositoryInterface;
use Modules\User\Http\Requests\V1\Api\UsersWhichMostTransactionsRequest;
use Modules\User\Repository\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
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
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::edit');
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

    public function usersWhichMostTransactions(UsersWhichMostTransactionsRequest $request, UserRepositoryInterface $userRepository, TransactionRepositoryInterface $transactionRepository)
    {
        try {
            $topUsersIds = $this->topUsersIdsWhichMostTransactions(userRepository: $userRepository, transactionRepository: $transactionRepository, limit: 3);

            $users = $userRepository
                ->freshQuery()
                ->whereIn('id', $topUsersIds)
                ->all(relations: [
                    'bankAccountCards' => fn(HasManyThrough $builder) => $builder->whereHas('sentTransactions')->orWhereHas('receivedTransactions'),
                    'bankAccountCards.sentTransactions',
                    'bankAccountCards.receivedTransactions'
                ])
                ->getCollection(asResource: true, closure: function (Collection $collection) {
                    return $collection->map(function (User $user) {
                        $user->transactions = $user->bankAccountCards->map(fn(BankAccountCard $bankAccountCard) => $bankAccountCard->allTransactions)->collapse();

                        $user->makeHidden('bankAccountCards');
                        return $user;
                    });
                });

            return apiResponse([
                'users' => $users,
            ], __('base::v1.successful_loaded'));
        } catch (\Exception $exception) {
            report($exception);
            return apiResponse([], $exception);
        }
    }

    private function topUsersIdsWhichMostTransactions(UserRepositoryInterface $userRepository, TransactionRepositoryInterface $transactionRepository, int $limit = 3)
    {
        return $userRepository->freshQuery()->getQuery()->select('id')->withCount([
            'bankAccountCards as total_transactions' => function (Builder $builder) use ($transactionRepository) {
                $builder->select(DB::raw('COUNT(ref_number)'))->join($transactionRepository->getModel()->getTable(), function (JoinClause $join) {
                    $join->on('transactions.from_bank_account_card_number', '=', 'bank_account_cards.number')
                        ->orOn('transactions.to_bank_account_card_number', '=', 'bank_account_cards.number');
                });
            }
        ])->orderByDesc('total_transactions')->limit(value: $limit)->pluck('id');
    }

}
