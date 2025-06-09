<?php

namespace App\Livewire;

use App\Mail\CardRequestNotification;
use App\Models\CardRequest;
use App\Models\Config;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class UserBankCards extends Component
{
    public $showRequestCardModal = false;
    public $cardType = '';
    public $phoneNumber = '';
    public $requestMessage = '';
    public $dashboardView = false;
    public $maxCards = null;
    public $isSubmitting = false;
    public $isRequestingCard = false;
    public $loadingAction = null;
    public $showCardDetails = [];
    
    protected $listeners = ['execute-method' => 'executeMethod'];

    /**
     * Vérifie si l'utilisateur a des comptes inactifs
     */
    public function hasInactiveAccounts()
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->accounts()->where('status', '!=', 'ACTIVE')->exists();
    }

    public function toggleCardDetails($cardId)
    {
        if (isset($this->showCardDetails[$cardId])) {
            unset($this->showCardDetails[$cardId]);
        } else {
            $this->showCardDetails[$cardId] = true;
        }
    }

    public function requestCard()
    {
        $this->isRequestingCard = true;

        // Si on est sur le dashboard, rediriger vers la page des cartes
        if ($this->dashboardView) {
            return redirect()->route('user.cards', ['locale' => app()->getLocale()]);
        }

        // Sinon, ouvrir la modale
        $this->showRequestCardModal = true;
        $this->isRequestingCard = false;
    }

    public function submitCardRequest()
    {
        Log::info('submitCardRequest started', [
            'cardType' => $this->cardType,
            'phoneNumber' => $this->phoneNumber,
            'showRequestCardModal' => $this->showRequestCardModal
        ]);

        try {
            $this->isSubmitting = true;
            Log::info('isSubmitting set to true');

            $this->validate([
                'cardType' => 'required|in:VISA,MASTERCARD,AMERICAN_EXPRESS',
                'phoneNumber' => 'required|string|max:20',
                'requestMessage' => 'nullable|string|max:500'
            ]);
            Log::info('Validation passed');

            /** @var User $user */
            $user = Auth::user();
            $account = $user->accounts()->where('status', 'ACTIVE')->first();

            if (!$account) {
                Log::warning('No active account found');
                $this->dispatch('alert', ['type' => 'error', 'message' => __('common.no_active_account')]);
                $this->closeModal();
                return;
            }

            Log::info('Creating card request');
            $cardRequest = CardRequest::create([
                'account_id' => $account->id,
                'card_type' => $this->cardType,
                'phone_number' => $this->phoneNumber,
                'message' => $this->requestMessage,
                'status' => 'PENDING'
            ]);

            // Send email notification to company
            try {
                $config = Config::first();
                if ($config && $config->bank_email) {
                    Mail::to($config->bank_email)->send(new CardRequestNotification($user, $cardRequest, 'new_request'));
                    Log::info('Card request notification email sent to company', ['email' => $config->bank_email]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send card request notification email: ' . $e->getMessage());
            }

            Log::info('Card request created successfully');
            $this->dispatch('alert', ['type' => 'success', 'message' => __('common.card_request_sent')]);

            // Fermer la modale immédiatement
            Log::info('About to call closeModal');
            $this->closeModal();
            Log::info('closeModal called from success path');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation exception caught', ['errors' => $e->errors()]);
            // Les erreurs de validation sont gérées automatiquement par Livewire
            // mais on s'assure que isSubmitting est réinitialisé
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            Log::error('Exception in submitCardRequest', ['message' => $e->getMessage()]);
            // Pour toute autre erreur, fermer la modale et afficher un message d'erreur
            $this->dispatch('alert', ['type' => 'error', 'message' => __('common.error_occurred')]);
            $this->closeModal();
        }
    }

    public function closeModal()
    {
        Log::info('closeModal called', ['showRequestCardModal_before' => $this->showRequestCardModal, 'isSubmitting_before' => $this->isSubmitting]);
        $this->showRequestCardModal = false;
        $this->isSubmitting = false;
        $this->cardType = 'VISA';
        $this->phoneNumber = '';
        $this->requestMessage = '';
        $this->isRequestingCard = false;
        $this->resetValidation();
        Log::info('closeModal completed', ['showRequestCardModal_after' => $this->showRequestCardModal, 'isSubmitting_after' => $this->isSubmitting]);
        $this->dispatch('modal-closed');
    }

    public function deleteCardRequest($requestId)
    {
        $this->processDeleteCardRequest($requestId);
    }
    
    public function processDeleteCardRequest($requestId)
    {
        $this->loadingAction = 'delete_request_' . $requestId;
        
        try {
            /** @var User $user */
            $user = Auth::user();

            // Récupérer la demande de carte
            $cardRequest = CardRequest::find($requestId);

            if (!$cardRequest) {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('common.card_request_not_found')]);
                return;
            }

            // Vérifier que la demande appartient à l'utilisateur connecté
            $userAccountIds = $user->accounts->pluck('id')->toArray();
            if (!in_array($cardRequest->account_id, $userAccountIds)) {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('common.unauthorized_action')]);
                return;
            }

            // Vérifier que la demande est en attente
            if ($cardRequest->status !== 'PENDING') {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('common.cannot_delete_processed_request')]);
                return;
            }

            // Send email notification to company about cancellation
            try {
                $config = Config::first();
                if ($config && $config->bank_email) {
                    Mail::to($config->bank_email)->send(new CardRequestNotification($user, $cardRequest, 'request_cancelled'));
                    Log::info('Card request cancellation notification sent to company', ['email' => $config->bank_email]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send card request cancellation notification: ' . $e->getMessage());
            }

            // Supprimer la demande
            $cardRequest->delete();

            $this->dispatch('alert', ['type' => 'success', 'message' => __('common.card_request_deleted_successfully')]);
            
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('common.error_deleting_card_request')]);
        } finally {
            $this->loadingAction = null;
            $this->dispatch('action-completed');
        }
    }

    public function render()
    {
        /** @var User $user */
        $user = Auth::user();
        $cardsQuery = $user->cards()->with('account');

        if ($this->maxCards) {
            $cardsQuery->limit($this->maxCards);
        }

        $cards = $cardsQuery->get();

        // Récupérer les demandes de cartes pour tous les comptes de l'utilisateur
        $cardRequests = CardRequest::whereIn('account_id', $user->accounts->pluck('id'))
            ->with(['account', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.user-bank-cards', compact('cards', 'cardRequests'));
    }
    
    public function executeMethod($method, $params = [])
    {
        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $params);
        }
    }
}
