<?php

namespace App\Livewire\Auth;

use App\Mail\AccountStatusNotification;
use App\Mail\AccountActivationMail;
use App\Mail\AccountPendingActivationMail;
use App\Mail\NewUserPendingNotificationMail;
use App\Models\Account;
use App\Models\AccountBlock;
use App\Models\Config;
use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
    // Removed generalError property - now using alert system
    public $validationErrors = [];

    // Ajoutez ces propriétés
    protected $queryString = ['step' => ['except' => 1]];

    public function mount()
    {
        $this->countries = Country::all();
        
        // Vérifier d'abord le step dans l'URL
        $urlStep = request()->get('step');
        
        // Si l'utilisateur accède à la page d'inscription sans paramètre step
        // et qu'il y a une session de succès, cela signifie qu'il revient
        // après avoir quitté la page de succès - on doit reset le flow
        if (!$urlStep && session('registration_success')) {
            $this->resetRegistrationFlow();
            return;
        }
        
        // Vérifier si on a une inscription réussie en session
        if (session('registration_success')) {
            $this->step = 4;
        } elseif ($urlStep && in_array($urlStep, [1, 2, 3, 4])) {
            $this->step = (int) $urlStep;
            // Restaurer les données depuis la session si on navigue par URL
            $this->restoreFromSession();
        } else {
            // Restaurer les données depuis la session si elles existent
            $this->restoreFromSession();
            // Si pas de données en session, commencer à l'étape 1
            if (!session('registration_data')) {
                $this->step = 1;
                session()->forget(['registration_success', 'success_user_name']);
            }
        }
    }

    // Méthode améliorée pour restaurer les données depuis la session
    protected function restoreFromSession()
    {
        $sessionData = session('registration_data', []);

        if (!empty($sessionData)) {
            // Restaurer les données de base
            $fieldsToRestore = [
                'first_name', 'last_name', 'gender', 'birth_date', 'marital_status', 'profession',
                'phone_number', 'country_id', 'region', 'city', 'postal_code', 'address',
                'email', 'password', 'password_confirmation', 'currency', 'type'
            ];
            
            foreach ($fieldsToRestore as $field) {
                if (isset($sessionData[$field]) && property_exists($this, $field)) {
                    $this->$field = $sessionData[$field];
                }
            }

            // Restaurer le step si présent dans la session et pas déjà défini par l'URL
            if (isset($sessionData['step']) && !request()->get('step')) {
                $this->step = $sessionData['step'];
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
                // Vérifier la taille du fichier (10MB = 10240KB)
                if ($this->identity_document->getSize() > 10485760) {  // 10MB en bytes
                    $this->dispatch('alert', ['type' => 'error', 'message' => __('register.field_errors.identity_document.max')]);
                    return;
                }
                $tempPath = 'temp/identity_' . session()->getId() . '_' . time() . '.' . $this->identity_document->getClientOriginalExtension();
                $this->identity_document->storeAs('', $tempPath, 'local');
                $data['identity_document_temp'] = $tempPath;
                $data['identity_document_name'] = $this->identity_document->getClientOriginalName();
            } catch (\Exception $e) {
                Log::warning('Failed to save identity document to temp: ' . $e->getMessage());
                $this->dispatch('alert', ['type' => 'error', 'message' => __('register.field_errors.identity_document.upload_failed')]);
            }
        }

        if ($this->address_document) {
            try {
                // Vérifier la taille du fichier (10MB = 10240KB)
                if ($this->address_document->getSize() > 10485760) {  // 10MB en bytes
                    $this->dispatch('alert', ['type' => 'error', 'message' => __('register.field_errors.address_document.max')]);
                    return;
                }
                $tempPath = 'temp/address_' . session()->getId() . '_' . time() . '.' . $this->address_document->getClientOriginalExtension();
                $this->address_document->storeAs('', $tempPath, 'local');
                $data['address_document_temp'] = $tempPath;
                $data['address_document_name'] = $this->address_document->getClientOriginalName();
            } catch (\Exception $e) {
                Log::warning('Failed to save address document to temp: ' . $e->getMessage());
                $this->dispatch('alert', ['type' => 'error', 'message' => __('register.field_errors.address_document.upload_failed')]);
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
            'identity_document.required' => __('register.field_errors.identity_document.required'),
            'identity_document.file' => __('register.field_errors.identity_document.file'),
            'identity_document.mimes' => __('register.field_errors.identity_document.mimes'),
            'identity_document.max' => __('register.field_errors.identity_document.max'),
            'address_document.required' => __('register.field_errors.address_document.required'),
            'address_document.file' => __('register.field_errors.address_document.file'),
            'address_document.mimes' => __('register.field_errors.address_document.mimes'),
            'address_document.max' => __('register.field_errors.address_document.max'),
        ];
    }

    // Méthode pour réinitialiser les erreurs
    protected function resetErrors()
    {
        // Reset any previous errors
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
            
            // Mettre à jour l'URL sans rechargement
            $this->dispatch('update-url', ['step' => $this->step]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->validationErrors = $e->errors();
            $firstError = collect($e->errors())->flatten()->first();
            $errorMessage = __('register.validation_error_message');
            if ($firstError) {
                $errorMessage .= ' ' . $firstError;
            }
            $this->dispatch('alert', ['type' => 'error', 'message' => $errorMessage]);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('register.error_message')]);
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
        
        // Mettre à jour l'URL sans rechargement
        $this->dispatch('update-url', ['step' => $this->step]);
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
                'identity_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',  // 10MB max
                'address_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',  // 10MB max
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
                $this->dispatch('alert', ['type' => 'error', 'message' => __('register.email_already_exists')]);
                return;
            }

            // Initialiser les chemins de documents
            $identityPath = null;
            $addressPath = null;

            // Traitement conditionnel des documents
            if ($this->identity_document) {
                try {
                    $identityFilename = 'identity_' . Str::random(10) . '.' . $this->identity_document->getClientOriginalExtension();
                    $identityPath = 'documents/' . $identityFilename;
                    $this->processAndSaveFile($this->identity_document, $identityPath);
                } catch (\Exception $e) {
                    Log::error('Failed to process identity document: ' . $e->getMessage());
                    $this->dispatch('alert', ['type' => 'error', 'message' => __('register.field_errors.identity_document.upload_failed')]);
                    return;
                }
            }

            if ($this->address_document) {
                try {
                    $addressFilename = 'address_' . Str::random(10) . '.' . $this->address_document->getClientOriginalExtension();
                    $addressPath = 'documents/' . $addressFilename;
                    $this->processAndSaveFile($this->address_document, $addressPath);
                } catch (\Exception $e) {
                    Log::error('Failed to process address document: ' . $e->getMessage());
                    $this->dispatch('alert', ['type' => 'error', 'message' => __('register.field_errors.address_document.upload_failed')]);
                    return;
                }
            }

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
                'currency' => $this->currency,
                'status' => 'INACTIVE',
            ]);

            // Associer le blocage par défaut au nouveau compte
            $defaultBlock = AccountBlock::where('reason', 'Vérification d\'identité')
                ->first();
            
            if ($defaultBlock) {
                // Associer le blocage par défaut au compte avec le statut 'inactive'
                $account->accountBlocks()->attach($defaultBlock->id, ['status' => 'inactive']);
            }

            // Envoyer l'e-mail selon le paramètre d'activation
            $config = Config::first();
            $canSelfActivate = $config ? $config->user_can_self_activate : true;
            
            try {
                if ($canSelfActivate) {
                    // Envoi du mail d'activation classique avec bouton
                    Mail::to($user->email)->send(new AccountActivationMail($user));
                    Log::info('Activation email sent successfully', ['user_id' => $user->id]);
                } else {
                    // Envoi du mail d'attente d'activation manuelle
                    Mail::to($user->email)->send(new AccountPendingActivationMail($user));
                    Log::info('Pending activation email sent successfully', ['user_id' => $user->id]);
                    
                    // Envoyer une notification à l'admin
                    $adminEmail = $config ? ($config->notification_email ?: $config->bank_email) : null;
                    if ($adminEmail) {
                        Mail::to($adminEmail)->send(new NewUserPendingNotificationMail($user));
                        Log::info('Admin notification sent for pending user', ['user_id' => $user->id, 'admin_email' => $adminEmail]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to send activation email', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            // Nettoyer les fichiers temporaires et la session
            $this->cleanupTempFiles();
            session()->forget('registration_data');

            // Passer à l'étape de succès sans rechargement
            $this->step = 4;
            
            // Sauvegarder l'état de succès en session
            session(['registration_success' => true, 'success_user_name' => $user->first_name]);
            
            // Dispatch un événement pour indiquer le succès
            $this->dispatch('registration-success');
            
            // Mettre à jour l'URL sans rechargement
            $this->dispatch('update-url', ['step' => 4]);
            
            // Afficher le message de succès selon la configuration
            if ($canSelfActivate) {
                $this->dispatch('alert', ['type' => 'success', 'message' => __('register.success_message')]);
            } else {
                $this->dispatch('alert', ['type' => 'info', 'message' => __('register.success_pending_message')]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->validationErrors = $e->errors();

            // Log spécifique pour l'erreur d'email déjà utilisé
            if (isset($e->errors()['email'])) {
                Log::error('RegisterForm: Email validation failed during registration.', [
                    'email' => $this->email,
                    'errors' => $e->errors(),
                    'message' => $e->getMessage()
                ]);
                $this->dispatch('alert', ['type' => 'error', 'message' => __('register.email_already_exists')]);
            } else {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('register.validation_error_message')]);
            }
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('register.error_message')]);
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

    /**
     * Réinitialise complètement le flow d'inscription
     */
    public function resetRegistrationFlow()
    {
        // Nettoyer toutes les sessions liées à l'inscription
        session()->forget(['registration_success', 'success_user_name', 'registration_data']);
        
        // Réinitialiser toutes les propriétés du composant
        $this->step = 1;
        $this->isSubmitting = false;
        $this->validationErrors = [];
        
        // Réinitialiser tous les champs
        $this->first_name = null;
        $this->last_name = null;
        $this->gender = null;
        $this->birth_date = null;
        $this->marital_status = null;
        $this->profession = null;
        $this->phone_number = null;
        $this->country_id = null;
        $this->region = null;
        $this->city = null;
        $this->postal_code = null;
        $this->address = null;
        $this->currency = null;
        $this->type = null;
        $this->email = null;
        $this->password = null;
        $this->password_confirmation = null;
        $this->identity_document = null;
        $this->address_document = null;
        $this->showPassword = false;
        $this->showPasswordConfirmation = false;
        
        // Nettoyer les fichiers temporaires
        $this->cleanupTempFiles();
        
        // Mettre à jour l'URL pour refléter l'étape 1
        $this->dispatch('update-url', ['step' => 1]);
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}
