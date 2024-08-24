<?php

namespace App\Rules;

use App\Models\Transaction;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class TransactionIsNotClaimed implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Find the transaction by its ID
        $transaction = Transaction::find($value);

        // Check if the transaction exists and if it is claimed

        if($transaction && $transaction->is_claimed) {
            $fail('This transaction was already claimed previously.');
        }
    }
}
