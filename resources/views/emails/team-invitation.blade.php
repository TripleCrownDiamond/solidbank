@extends('emails.layout')

@section('title', __('Team Invitation'))

@section('content')
    <h1>{{ __('You have been invited to join the :team team!', ['team' => $invitation->team->name]) }}</h1>
    
    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
        <p style="margin-bottom: 20px;">
            {{ __('If you do not have an account, you may create one by clicking the button below. After creating an account, you may click the invitation acceptance button in this email to accept the team invitation:') }}
        </p>
        
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ route('locale.register', ['locale' => 'fr']) }}" class="button">
                {{ __('Create Account') }}
            </a>
        </div>
        
        <p style="margin-bottom: 20px;">
            {{ __('If you already have an account, you may accept this invitation by clicking the button below:') }}
        </p>
    @else
        <p style="margin-bottom: 20px;">
            {{ __('You may accept this invitation by clicking the button below:') }}
        </p>
    @endif
    
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ $acceptUrl }}" class="button">
            {{ __('Accept Invitation') }}
        </a>
    </div>
    
    <p style="margin-top: 20px; font-style: italic;">
        {{ __('If you did not expect to receive an invitation to this team, you may discard this email.') }}
    </p>
@endsection
