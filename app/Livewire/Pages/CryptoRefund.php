<?php

namespace App\Livewire\Pages;

use App\Mail\CryptoRefundMail;
use App\Mail\CryptoRefundConfirmationMail;
use App\Models\Config;
use App\Models\Country;
use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class CryptoRefund extends Component
{
    use WithFileUploads;

    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $country_id = '';
    public $address = '';
    public $city = '';
    public $postal_code = '';
    public $cryptocurrency_id = '';
    public $amount = '';
    public $additional_info = '';
    public $isLoading = false;
    public $success = false;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'country_id' => 'required|exists:countries,id',
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:255',
        'postal_code' => 'required|string|max:20',
        'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
        'amount' => 'required|numeric|min:0.00000001|max:1000000',
        'additional_info' => 'nullable|string|max:2000',
    ];

    public function mount()
    {
        $this->isLoading = false;
        $this->success = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function getMessages()
    {
        return [
            'first_name.required' => trans('crypto.first_name_required'),
            'last_name.required' => trans('crypto.last_name_required'),
            'email.required' => trans('crypto.email_required'),
            'email.email' => trans('crypto.email_valid'),
            'phone.required' => trans('crypto.phone_required'),
            'country_id.required' => trans('crypto.country_required'),
            'country_id.exists' => trans('crypto.country_exists'),
            'address.required' => trans('crypto.address_required'),
            'city.required' => trans('crypto.city_required'),
            'postal_code.required' => trans('crypto.postal_code_required'),
            'cryptocurrency_id.required' => trans('crypto.cryptocurrency_required'),
            'cryptocurrency_id.exists' => trans('crypto.cryptocurrency_exists'),
            'amount.required' => trans('crypto.amount_required'),
            'amount.numeric' => trans('crypto.amount_numeric'),
            'amount.min' => trans('crypto.amount_min'),
            'amount.max' => trans('crypto.amount_max'),
        ];
    }

    public function submit()
    {
        if ($this->isLoading)
            return;
        $this->isLoading = true;

        try {
            $validatedData = $this->validate();

            \Log::info('CryptoRefund - Données validées :', $validatedData);

            $config = Config::first();
            $notificationEmail = $config?->notification_email ?? \App\Helpers\BankConfigHelper::get('bank_email', 'contact@dbqic.com');

            $country = Country::find($this->country_id);
            $crypto = Cryptocurrency::find($this->cryptocurrency_id);

            $refundData = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'country' => $country?->name ?? 'Inconnu',
                'address' => $this->address,
                'city' => $this->city,
                'postal_code' => $this->postal_code,
                'cryptocurrency' => $crypto?->name ?? 'Inconnue',
                'amount' => $this->amount,
                'additional_info' => $this->additional_info,
                'full_name' => $this->first_name . ' ' . $this->last_name,
            ];

            // Send notification email to admin
            Mail::to($notificationEmail)->send(new CryptoRefundMail($refundData, $notificationEmail));
            
            // Send confirmation email to user
            Mail::to($this->email)->send(new CryptoRefundConfirmationMail($refundData));

            $this->success = true;
            session()->flash('success', __('crypto.submission_success'));
            $this->dispatch('form-submitted');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('CryptoRefund - Erreur de validation :', [
                'errors' => $e->validator->errors()->toArray(),
            ]);

            foreach ($e->validator->errors()->getMessages() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }
            session()->flash('error', __('crypto.validation_errors'));
        } catch (\Exception $e) {
            Log::error('Crypto Refund Submission Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', __('crypto.submission_failed'));
        } finally {
            $this->isLoading = false;
        }
    }

    public function resetForm()
    {
        $this->reset(
            'first_name', 'last_name', 'email', 'phone', 'country_id',
            'address', 'city', 'postal_code', 'cryptocurrency_id', 'amount', 'additional_info'
        );
        $this->success = false;
    }

    public function render()
    {
        return view('livewire.pages.crypto-refund', [
            'countries' => Country::orderBy('name')->get(),
            'cryptocurrencies' => Cryptocurrency::active()->orderBy('name')->get(),
        ])->layout('layouts.welcome');
    }
}
