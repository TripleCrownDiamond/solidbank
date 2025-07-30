<?php

namespace App\Livewire\Pages;

use App\Mail\LoanRequestConfirmationMail;
use App\Mail\LoanRequestMail;
use App\Models\Config;
use App\Models\Country;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class LoanRequest extends Component
{
    use WithFileUploads;

    // Informations personnelles
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $birth_date = '';
    public $marital_status = '';
    public $country_id = '';
    public $address = '';
    public $city = '';
    public $postal_code = '';
    // Informations de prêt
    public $loan_amount = '';
    public $loan_duration = '';
    public $loan_purpose = '';
    public $monthly_income = '';
    public $employment_status = '';
    public $currency = 'EUR';
    // Autres
    public $additional_info = '';
    public $success = false;
    public $isSubmitting = false;
    public $isLoading = false;
    // Simulateur
    public $simulated_amount = '';
    public $simulated_duration = '';
    public $simulated_currency = 'EUR';
    public $monthly_payment = 0;
    public $loan_rate = 5.0;
    public $total_payment = 0;
    public $total_interest = 0;

    protected $rules = [
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'required|min:2|max:100',
        'email' => 'required|email|max:255',
        'phone' => 'required|min:8|max:20',
        'birth_date' => 'required|date|before:today',
        'marital_status' => 'required|in:single,married,divorced,widowed',
        'country_id' => 'required|exists:countries,id',
        'address' => 'required|min:10|max:255',
        'city' => 'required|min:2|max:100',
        'postal_code' => 'required|min:3|max:20',
        'loan_amount' => 'required|numeric|min:1',
        'loan_duration' => 'required|integer|min:6|max:360',
        'loan_purpose' => 'required|min:10|max:500',
        'monthly_income' => 'required|numeric|min:1',
        'employment_status' => 'required|in:employed,self_employed,unemployed,retired,student',
        'currency' => 'required|in:EUR,USD,GBP,CHF,SEK,NOK,DKK,PLN,CZK,HUF,RON,BGN,HRK',
        'additional_info' => 'nullable|max:1000',
    ];

    public function mount()
    {
        $config = Config::first();
        $this->loan_rate = $config?->loan_rate ?? 5.0;
        $this->simulated_amount = 10000;
        $this->simulated_duration = 12;
        $this->calculateLoan();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedSimulatedAmount()
    {
        $this->calculateLoan();
    }

    public function updatedSimulatedDuration()
    {
        $this->calculateLoan();
    }

    public function updatedSimulatedCurrency()
    {
        $this->calculateLoan();
    }

    public function updatedCurrency()
    {
        $this->validateOnly('currency');
        $this->dispatch('currency-updated');
    }

    public function calculateLoan()
    {
        if ($this->simulated_amount && $this->simulated_duration) {
            $config = Config::first();
            $rate = $config?->loan_rate ?? 5.0;

            $monthlyRate = $rate / 100 / 12;
            $amount = (float) $this->simulated_amount;
            $months = (int) $this->simulated_duration;

            $this->monthly_payment = $monthlyRate > 0
                ? ($amount * $monthlyRate * (1 + $monthlyRate) ** $months) / ((1 + $monthlyRate) ** $months - 1)
                : $amount / $months;

            $this->total_payment = $this->monthly_payment * $months;
            $this->total_interest = $this->total_payment - $amount;
        }
    }

    public function submit()
    {
        $this->isLoading = true;

        try {
            $validatedData = $this->validate();

            $config = Config::first();
            $notificationEmail = $config?->notification_email ?? \App\Helpers\BankConfigHelper::get('bank_email', 'contact@dbqic.com');

            $loanData = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'birth_date' => $this->birth_date,
                'marital_status' => $this->marital_status,
                'country' => Country::find($this->country_id)->name,
                'address' => $this->address,
                'city' => $this->city,
                'postal_code' => $this->postal_code,
                'loan_amount' => $this->loan_amount,
                'loan_duration' => $this->loan_duration,
                'loan_purpose' => $this->loan_purpose,
                'monthly_income' => $this->monthly_income,
                'employment_status' => $this->employment_status,
                'currency' => $this->currency,
                'additional_info' => $this->additional_info,
            ];

            // Envoi de l'email à l'admin
            Mail::to($notificationEmail)->send(new LoanRequestMail($loanData));

            // Envoi de l'email de confirmation à l'utilisateur avec la locale actuelle
            Mail::to($this->email)->send(new LoanRequestConfirmationMail($loanData, app()->getLocale()));

            // Message de succès
            session()->flash('success', __('loan.success.submission'));
            $this->success = true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->validator->errors()->getMessages() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }
            session()->flash('error', __('loan.errors.validation'));
        } catch (\Exception $e) {
            \Log::error('Loan request error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = __('loan.errors.submission');
            if (app()->environment('local')) {
                $errorMessage .= ' Détails: ' . $e->getMessage();
            }

            session()->flash('error', $errorMessage);
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        $currency_symbols = [
            'EUR' => '€', 'USD' => '$', 'GBP' => '£', 'CHF' => 'CHF',
            'SEK' => 'kr', 'NOK' => 'kr', 'DKK' => 'kr', 'PLN' => 'zł',
            'CZK' => 'Kč', 'HUF' => 'Ft', 'RON' => 'lei', 'BGN' => 'лв',
            'HRK' => 'kn'
        ];

        return view('livewire.pages.loan-request', [
            'countries' => Country::all(),
            'loan_rate' => $this->loan_rate,
            'currency_symbol' => $currency_symbols[$this->simulated_currency] ?? '€',
            'isLoading' => $this->isLoading
        ])->layout('layouts.welcome');
    }
}
