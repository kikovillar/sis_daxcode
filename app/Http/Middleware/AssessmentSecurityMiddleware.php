<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AssessmentSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se é uma tentativa de avaliação
        if ($request->route('studentAssessment')) {
            $studentAssessment = $request->route('studentAssessment');
            
            // Verificar se $studentAssessment é válido
            if (!$studentAssessment || !method_exists($studentAssessment, 'assessment')) {
                return $next($request);
            }
            
            $assessment = $studentAssessment->assessment;
            
            // Verificar se $assessment é válido
            if (!$assessment) {
                return $next($request);
            }
            
            // Verificar configurações de segurança
            $settings = $assessment->settings ?? [];
            
            // Verificar browser lockdown
            if ($settings['browser_lockdown'] ?? false) {
                $this->checkBrowserLockdown($request);
            }
            
            // Verificar tentativas de cola
            if ($settings['prevent_copy_paste'] ?? false) {
                $this->logSuspiciousActivity($request, $studentAssessment);
            }
            
            // Verificar webcam se necessário
            if ($settings['require_webcam'] ?? false) {
                $this->checkWebcamRequirement($request);
            }
            
            // Log de atividade
            $this->logAssessmentActivity($request, $studentAssessment);
        }
        
        return $next($request);
    }
    
    private function checkBrowserLockdown(Request $request)
    {
        $userAgent = $request->userAgent();
        
        // Garantir que $userAgent é uma string
        if (!is_string($userAgent)) {
            $userAgent = '';
        }
        
        // Detectar se está em modo fullscreen ou browser seguro
        if (strpos($userAgent, 'SEB') === false && 
            !$request->header('X-Fullscreen-Mode')) {
            
            Log::warning('Tentativa de acesso sem browser lockdown', [
                'ip' => $request->ip(),
                'user_agent' => $userAgent,
                'user_id' => auth()->id()
            ]);
        }
    }
    
    private function logSuspiciousActivity(Request $request, $studentAssessment)
    {
        // Detectar atividades suspeitas via JavaScript será implementado no frontend
        if ($request->has('suspicious_activity')) {
            Log::warning('Atividade suspeita detectada', [
                'student_assessment_id' => $studentAssessment->id,
                'activity' => $request->input('suspicious_activity'),
                'timestamp' => now(),
                'ip' => $request->ip()
            ]);
        }
    }
    
    private function checkWebcamRequirement(Request $request)
    {
        // Verificar se webcam está ativa (implementação via JavaScript no frontend)
        if (!$request->header('X-Webcam-Active')) {
            Log::warning('Webcam não detectada em avaliação que requer', [
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
        }
    }
    
    private function logAssessmentActivity(Request $request, $studentAssessment)
    {
        Log::info('Atividade de avaliação', [
            'student_assessment_id' => $studentAssessment->id,
            'action' => $request->route()->getActionMethod(),
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);
    }
}