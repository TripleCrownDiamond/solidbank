<?php

namespace App\Mail\Traits;

trait AntiSpamHeaders
{
    /**
     * Applique les en-têtes anti-spam pour améliorer la délivrabilité
     *
     * @param \Illuminate\Mail\Mailables\Envelope|\Illuminate\Mail\Message $message
     * @param string $emailType Type d'e-mail (activation_email, password_reset, etc.)
     * @return void
     */
    protected function applyAntiSpamHeaders($message, string $emailType = 'default')
    {
        // Récupérer les en-têtes de base
        $baseHeaders = config('mail_headers.anti_spam_headers', []);
        
        // Récupérer les en-têtes spécifiques au type d'e-mail
        $specificHeaders = config("mail_headers.{$emailType}", []);
        
        // Fusionner les en-têtes
        $allHeaders = array_merge($baseHeaders, $specificHeaders);
        
        // Appliquer les en-têtes
        $headers = $message->getHeaders();
        
        foreach ($allHeaders as $name => $value) {
            // Ignorer Return-Path car il doit être défini différemment
            if ($name !== 'Return-Path') {
                $headers->addTextHeader($name, $value);
            }
        }
        
        // Ajouter des en-têtes dynamiques
        $headers->addTextHeader('X-Sent-Time', now()->toISOString())
                ->addTextHeader('X-Message-ID', uniqid('BRED-', true))
                ->addTextHeader('X-Sender-IP', request()->ip() ?? 'unknown')
                ->addTextHeader('X-User-Agent', request()->userAgent() ?? 'system');
    }
    
    /**
     * Valide le contenu de l'e-mail pour éviter les déclencheurs de spam
     *
     * @param mixed $content
     * @return array
     */
    protected function validateEmailContent($content): array
    {
        // Convertir le contenu en chaîne de caractères
        $contentString = $this->extractContentAsString($content);
        
        if (empty($contentString)) {
            return [];
        }
        
        $spamTriggers = [
            'urgent', 'gratuit', 'promotion', 'offre spéciale', 'limité',
            'cliquez ici', 'agissez maintenant', 'félicitations', 'gagnant',
            'argent facile', 'revenus', 'investissement', 'prêt', 'crédit'
        ];
        
        $warnings = [];
        $contentLower = strtolower($contentString);
        
        foreach ($spamTriggers as $trigger) {
            if (strpos($contentLower, $trigger) !== false) {
                $warnings[] = "Mot déclencheur détecté: {$trigger}";
            }
        }
        
        // Vérifier le ratio texte/HTML
        $textLength = strlen(strip_tags($contentString));
        $htmlLength = strlen($contentString);
        
        if ($htmlLength > 0 && ($textLength / $htmlLength) < 0.3) {
            $warnings[] = 'Ratio texte/HTML trop faible (risque de spam)';
        }
        
        // Vérifier les liens
        if (preg_match_all('/http:\/\//', $contentString) > 0) {
            $warnings[] = 'Liens HTTP détectés (utiliser HTTPS)';
        }
        
        return $warnings;
    }
    
    /**
     * Extrait le contenu sous forme de chaîne de caractères
     *
     * @param mixed $content
     * @return string
     */
    private function extractContentAsString($content): string
    {
        if (is_string($content)) {
            return $content;
        }
        
        if (is_object($content)) {
            // Gestion des objets Symfony\Component\Mime\Part\TextPart
            if (method_exists($content, 'getBody')) {
                return $this->extractContentAsString($content->getBody());
            }
            
            if (method_exists($content, '__toString')) {
                return (string) $content;
            }
            
            if (method_exists($content, 'toString')) {
                return $content->toString();
            }
        }
        
        return '';
    }
    
    /**
     * Génère un ID unique pour le message
     *
     * @param string $prefix
     * @return string
     */
    protected function generateMessageId(string $prefix = 'BRED'): string
    {
        return $prefix . '-' . date('Ymd') . '-' . uniqid();
    }
    
    /**
     * Applique les en-têtes de conformité RGPD
     *
     * @param \Illuminate\Mail\Message $message
     * @return void
     */
    protected function applyGdprHeaders($message)
    {
        $message->getHeaders()
            ->addTextHeader('X-GDPR-Compliant', 'true')
            ->addTextHeader('X-Data-Processing-Basis', 'legitimate-interest')
            ->addTextHeader('X-Retention-Policy', '30-days')
            ->addTextHeader('X-Privacy-Policy', config('app.url') . '/privacy');
    }
}