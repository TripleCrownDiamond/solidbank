<?php

namespace App\Livewire\Auth;

use App\Models\Account;
use App\Models\Config;
use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegisterForm extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $isSubmitting = false;
    public $countries;

    // Étape 1
    public $first_name,
        $last_name,
        $gender,
        $birth_date,
        $marital_status,
        $profession;

    // Étape 2
    public $phone_number,
        $country_id,
        $region,
        $city,
        $postal_code,
        $address;

    // Étape 3
    public $currency,
        $type,
        $email,
        $password,
        $password_confirmation;

    public $identity_document,
        $address_document;

    public $showPassword = false;
    public $showPasswordConfirmation = false;
    // Variables pour la gestion des erreurs
    public $generalError = null;
    public $validationErrors = [];

    // Ajoutez ces propriétés
    protected $queryString = ['step' => ['except' => 1]];

    public function mount()
    {
        $this->countries = Country::all();
        $this->restoreFromSession();
    }

    // Méthode améliorée pour restaurer les données depuis la session
    protected function restoreFromSession()
    {
        $sessionData = session('registration_data', []);

        if (!empty($sessionData)) {
            // Restaurer les données de base
            foreach ($sessionData as $key => $value) {
                if (property_exists($this, $key) && !in_array($key, ['identity_document', 'address_document'])) {
                    $this->$key = $value;
                }
            }

            // Restaurer les fichiers depuis la session si ils existent
            if (isset($sessionData['identity_document_temp']) && Storage::exists($sessionData['identity_document_temp'])) {
                // Les fichiers temporaires seront gérés différemment
            }
            if (isset($sessionData['address_document_temp']) && Storage::exists($sessionData['address_document_temp'])) {
                // Les fichiers temporaires seront gérés différemment
            }
        }
    }

    // Méthode améliorée pour sauvegarder en session
    protected function saveToSession()
    {
        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'marital_status' => $this->marital_status,
            'profession' => $this->profession,
            'phone_number' => $this->phone_number,
            'country_id' => $this->country_id,
            'region' => $this->region,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'currency' => $this->currency,
            'type' => $this->type,
            'step' => $this->step,
        ];

        // Sauvegarder les fichiers temporairement si ils existent
        if ($this->identity_document) {
            try {
                $tempPath = 'temp/identity_' . session()->getId() . '_' . time() . '.' . $this->identity_document->getClientOriginalExtension();
                $this->identity_document->storeAs('', $tempPath, 'local');
                $data['identity_document_temp'] = $tempPath;
                $data['identity_document_name'] = $this->identity_document->getClientOriginalName();
            } catch (\Exception $e) {
                Log::warning('Failed to save identity document to temp: ' . $e->getMessage());
            }
        }

        if ($this->address_document) {
            try {
                $tempPath = 'temp/address_' . session()->getId() . '_' . time() . '.' . $this->address_document->getClientOriginalExtension();
                $this->address_document->storeAs('', $tempPath, 'local');
                $data['address_document_temp'] = $tempPath;
                $data['address_document_name'] = $this->address_document->getClientOriginalName();
            } catch (\Exception $e) {
                Log::warning('Failed to save address document to temp: ' . $e->getMessage());
            }
        }

        session(['registration_data' => $data]);
    }

    // Méthode pour obtenir le pays sélectionné
    public function getSelectedCountryProperty()
    {
        if (!$this->country_id) {
            return null;
        }
        return $this->countries->firstWhere('id', $this->country_id);
    }

    protected function messages()
    {
        return [
            'first_name.required' => __('register.validation.required'),
            'last_name.required' => __('register.validation.required'),
            'gender.required' => __('register.validation.required'),
            'gender.in' => __('register.validation.in'),
            'birth_date.required' => __('register.validation.required'),
            'birth_date.date' => __('register.validation.date'),
            'marital_status.required' => __('register.validation.required'),
            'profession.required' => __('register.validation.required'),
            'phone_number.required' => __('register.validation.required'),
            'country_id.required' => __('register.validation.required'),
            'country_id.exists' => __('register.validation.exists'),
            'region.required' => __('register.validation.required'),
            'city.required' => __('register.validation.required'),
            'postal_code.required' => __('register.validation.required'),
            'address.required' => __('register.validation.required'),
            'email.required' => __('register.validation.required'),
            'email.email' => __('register.validation.email'),
            'email.unique' => __('register.validation.unique'),
            'password.required' => __('register.validation.required'),
            'password.confirmed' => __('register.validation.confirmed'),
            'password.min' => __('register.validation.min'),
            'currency.required' => __('register.validation.required'),
            'type.required' => __('register.validation.required'),
            'identity_document.required' => __('register.validation.required'),
            'identity_document.file' => __('register.validation.file'),
            'identity_document.mimes' => __('register.validation.mimes', ['values' => 'PDF, JPG, PNG']),
            'address_document.required' => __('register.validation.required'),
            'address_document.file' => __('register.validation.file'),
            'address_document.mimes' => __('register.validation.mimes', ['values' => 'PDF, JPG, PNG']),
        ];
    }

    // Méthode pour réinitialiser les erreurs
    protected function resetErrors()
    {
        $this->generalError = null;
        $this->validationErrors = [];
        $this->resetErrorBag();
    }

    public function nextStep()
    {
        try {
            $this->resetErrors();
            $this->isSubmitting = true;

            $this->validateStep();
            $this->step++;
            $this->saveToSession();
            $this->dispatch('$refresh');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->validationErrors = $e->errors();
            $this->generalError = __('register.validation_error_message');
            throw $e;
        } catch (\Exception $e) {
            $this->generalError = __('register.error_message');
            Log::error('RegisterForm nextStep error: ' . $e->getMessage(), [
                'step' => $this->step,
                'user_data' => $this->getCleanUserData()
            ]);
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function prevStep()
    {
        $this->resetErrors();
        $this->step--;
        $this->dispatch('$refresh');
    }

    public function updatedCountryId($value)
    {
        // Cette méthode est appelée automatiquement quand country_id change
    }

    public function validateStep()
    {
        $rules = match ($this->step) {
            1 => [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'gender' => 'required|in:MALE,FEMALE,OTHER',
                'birth_date' => 'required|date|before:today',
                'marital_status' => 'required|string|max:255',
                'profession' => 'required|string|max:255',
            ],
            2 => [
                'phone_number' => 'required|string|max:20',
                'country_id' => 'required|exists:countries,id',
                'region' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
                'address' => 'required|string|max:500',
            ],
            3 => [
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|confirmed|min:8',
                'currency' => 'required|string|in:EUR,USD,GBP,CAD,CHF',
                'type' => 'required|string|in:CHECKING,SAVINGS',
                'identity_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',  // 10MB max
                'address_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',  // 10MB max
            ],
            default => [],
        };

        $this->validate($rules);
    }

    // Méthode utilitaire pour obtenir les données utilisateur nettoyées (sans mots de passe)
    protected function getCleanUserData()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'step' => $this->step,
        ];
    }

    // Méthode pour supprimer les documents temporaires
    protected function cleanupTempFiles()
    {
        $sessionData = session('registration_data', []);

        if (isset($sessionData['identity_document_temp']) && Storage::exists($sessionData['identity_document_temp'])) {
            Storage::delete($sessionData['identity_document_temp']);
        }

        if (isset($sessionData['address_document_temp']) && Storage::exists($sessionData['address_document_temp'])) {
            Storage::delete($sessionData['address_document_temp']);
        }
    }

    // Méthodes pour supprimer les documents
    public function removeIdentityDocument()
    {
        $this->identity_document = null;
    }

    public function removeAddressDocument()
    {
        $this->address_document = null;
    }

    public function submit()
    {
        try {
            $this->resetErrors();
            $this->isSubmitting = true;

            // Validation finale
            $this->validateStep();

            // Vérifier une dernière fois que l'email n'existe pas (protection contre la double soumission)
            if (User::where('email', $this->email)->exists()) {
                $this->addError('email', __('register.validation.unique'));
                $this->generalError = __('register.email_already_exists');
                return;
            }

            // Générer un nom unique pour les fichiers
            $identityFilename = 'identity_' . Str::random(10) . '.' . $this->identity_document->getClientOriginalExtension();
            $addressFilename = 'address_' . Str::random(10) . '.' . $this->address_document->getClientOriginalExtension();

            // Chemins de stockage
            $identityPath = 'documents/' . $identityFilename;
            $addressPath = 'documents/' . $addressFilename;

            // Compression et sauvegarde des fichiers
            $this->processAndSaveFile($this->identity_document, $identityPath);
            $this->processAndSaveFile($this->address_document, $addressPath);

            // Créer l'utilisateur
            $user = User::create([
                'name' => $this->first_name . ' ' . $this->last_name,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'gender' => $this->gender,
                'birth_date' => $this->birth_date,
                'marital_status' => $this->marital_status,
                'profession' => $this->profession,
                'phone_number' => $this->phone_number,
                'country_id' => $this->country_id,
                'region' => $this->region,
                'city' => $this->city,
                'postal_code' => $this->postal_code,
                'address' => $this->address,
                'email' => $this->email,
                'email_verified_at' => now(),
                'password' => Hash::make($this->password),
                'identity_document_url' => $identityPath,
                'address_document_url' => $addressPath,
                'is_admin' => false,
            ]);

            // Créer le compte utilisateur
            // Get config for account number generation
            $config = Config::first();
            $prefix = $config ? $config->account_prefix : 'ACC';
            $length = $config ? $config->account_length : 10;

            // Generate unique account number with prefix and random digits
            $numberLength = $length - strlen($prefix);
            do {
                $accountNumber = $prefix . str_pad(rand(0, pow(10, $numberLength) - 1), $numberLength, '0', STR_PAD_LEFT);
            } while (Account::where('account_number', $accountNumber)->exists());

            $account = Account::create([
                'user_id' => $user->id,
                'account_number' => $accountNumber,
                'balance' => 0,
                'type' => $this->type,
                'currency' => 'EUR',
                'status' => 'INACTIVE',
            ]);

            // Nettoyer les fichiers temporaires et la session
            $this->cleanupTempFiles();
            session()->forget('registration_data');

            // Passer à l'étape de succès
            // event(new Registered($user));
            $this->step = 4;
            session()->flash('success', __('register.success_message'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->validationErrors = $e->errors();
            $this->generalError = __('register.validation_error_message');

            // Log spécifique pour l'erreur d'email déjà utilisé
            if (isset($e->errors()['email'])) {
                Log::error('RegisterForm: Email validation failed during registration.', [
                    'email' => $this->email,
                    'errors' => $e->errors(),
                    'message' => $e->getMessage()
                ]);
                $this->generalError = __('register.email_already_exists');
            }
        } catch (\Exception $e) {
            $this->generalError = __('register.error_message');
            Log::error('RegisterForm: Registration failed.', [
                'error' => $e->getMessage(),
                'user_data' => $this->getCleanUserData(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->isSubmitting = false;
        }
    }

    // Méthode utilitaire pour traiter et sauvegarder les fichiers
    protected function processAndSaveFile($file, $path)
    {
        if ($file->getMimeType() === 'image/jpeg') {
            $image = imagecreatefromjpeg($file->getRealPath());
            imagejpeg($image, storage_path('app/public/' . $path), 75);
            imagedestroy($image);
        } elseif ($file->getMimeType() === 'image/png') {
            $image = imagecreatefrompng($file->getRealPath());
            imagepng($image, storage_path('app/public/' . $path), 6);
            imagedestroy($image);
        } else {
            $file->storeAs('documents', basename($path), 'public');
        }
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}
