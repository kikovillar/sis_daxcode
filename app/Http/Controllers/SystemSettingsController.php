<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemSettingsController extends Controller
{
    /**
     * Mostrar configurações do sistema
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }
        
        $settings = $this->getSystemSettings();
        
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * Atualizar configurações do sistema
     */
    public function update(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }
        
        $validated = $request->validate([
            'system_name' => 'nullable|string|max:255',
            'system_description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
            'primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'footer_text' => 'nullable|string|max:255',
            'maintenance_mode' => 'boolean',
            'allow_registration' => 'boolean',
            'max_file_size' => 'nullable|integer|min:1|max:10240',
            'session_timeout' => 'nullable|integer|min:5|max:1440',
        ]);
        
        $settings = $this->getSystemSettings();
        
        // Upload da logo
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            try {
                // Garantir que o diretório existe
                $logoDir = storage_path('app/public/system/logos');
                if (!file_exists($logoDir)) {
                    mkdir($logoDir, 0755, true);
                }
                
                // Remover logo antiga se existir
                if (isset($settings['logo_path']) && $settings['logo_path'] && !empty($settings['logo_path'])) {
                    $oldLogoPath = storage_path('app/public/' . $settings['logo_path']);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }
                
                // Gerar nome único para o arquivo
                $file = $request->file('logo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $relativePath = 'system/logos/' . $filename;
                
                // Mover o arquivo
                $file->move(storage_path('app/public/system/logos'), $filename);
                
                $validated['logo_path'] = $relativePath;
                
            } catch (\Exception $e) {
                return back()->with('error', 'Erro ao fazer upload da logo: ' . $e->getMessage());
            }
        }
        
        // Upload do favicon
        if ($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
            try {
                // Garantir que o diretório existe
                $faviconDir = storage_path('app/public/system/favicons');
                if (!file_exists($faviconDir)) {
                    mkdir($faviconDir, 0755, true);
                }
                
                // Remover favicon antigo se existir
                if (isset($settings['favicon_path']) && $settings['favicon_path'] && !empty($settings['favicon_path'])) {
                    $oldFaviconPath = storage_path('app/public/' . $settings['favicon_path']);
                    if (file_exists($oldFaviconPath)) {
                        unlink($oldFaviconPath);
                    }
                }
                
                // Gerar nome único para o arquivo
                $file = $request->file('favicon');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $relativePath = 'system/favicons/' . $filename;
                
                // Mover o arquivo
                $file->move(storage_path('app/public/system/favicons'), $filename);
                
                $validated['favicon_path'] = $relativePath;
                
            } catch (\Exception $e) {
                return back()->with('error', 'Erro ao fazer upload do favicon: ' . $e->getMessage());
            }
        }
        
        // Salvar configurações
        foreach ($validated as $key => $value) {
            if ($key !== 'logo' && $key !== 'favicon') {
                $this->setSetting($key, $value);
            }
        }
        
        // Limpar cache
        Cache::forget('system_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }
    
    /**
     * Resetar logo para padrão
     */
    public function resetLogo()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }
        
        $settings = $this->getSystemSettings();
        
        // Remover logo atual
        if (isset($settings['logo_path']) && $settings['logo_path'] && !empty($settings['logo_path'])) {
            $logoPath = storage_path('app/public/' . $settings['logo_path']);
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }
        }
        
        $this->setSetting('logo_path', null);
        
        // Limpar cache
        Cache::forget('system_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Logo resetada para o padrão!');
    }
    
    /**
     * Preview das configurações
     */
    public function preview(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }
        
        $settings = $this->getSystemSettings();
        
        // Aplicar configurações temporárias do preview
        if ($request->has('system_name')) {
            $settings['system_name'] = $request->input('system_name');
        }
        if ($request->has('primary_color')) {
            $settings['primary_color'] = $request->input('primary_color');
        }
        if ($request->has('secondary_color')) {
            $settings['secondary_color'] = $request->input('secondary_color');
        }
        
        return view('admin.settings.preview', compact('settings'));
    }
    
    /**
     * Exportar configurações
     */
    public function export()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }
        
        $settings = $this->getSystemSettings();
        
        // Remover caminhos de arquivos do export (por segurança)
        unset($settings['logo_path'], $settings['favicon_path']);
        
        $export = [
            'export_date' => now()->toISOString(),
            'system_version' => '1.0',
            'settings' => $settings
        ];
        
        $filename = 'system_settings_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($export)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Importar configurações
     */
    public function import(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }
        
        $request->validate([
            'settings_file' => 'required|file|mimes:json'
        ]);
        
        $content = file_get_contents($request->file('settings_file')->path());
        $data = json_decode($content, true);
        
        if (!$data || !isset($data['settings'])) {
            return back()->with('error', 'Arquivo de configurações inválido.');
        }
        
        // Importar apenas configurações seguras
        $safeSettings = [
            'system_name', 'system_description', 'primary_color', 
            'secondary_color', 'footer_text', 'allow_registration',
            'max_file_size', 'session_timeout'
        ];
        
        $imported = 0;
        foreach ($data['settings'] as $key => $value) {
            if (in_array($key, $safeSettings)) {
                $this->setSetting($key, $value);
                $imported++;
            }
        }
        
        // Limpar cache
        Cache::forget('system_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', "{$imported} configuração(ões) importada(s) com sucesso!");
    }
    
    /**
     * Obter todas as configurações do sistema
     */
    private function getSystemSettings()
    {
        return Cache::remember('system_settings', 3600, function () {
            // Verificar se a tabela existe
            try {
                $settings = DB::table('system_settings')->pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                // Se a tabela não existir, retornar apenas os padrões
                $settings = [];
            }
            
            // Valores padrão
            $defaults = [
                'system_name' => 'Sistema de Avaliacao',
                'system_description' => 'Plataforma completa para criacao e gestao de avaliacoes educacionais',
                'logo_path' => null,
                'favicon_path' => null,
                'primary_color' => '#3B82F6',
                'secondary_color' => '#1F2937',
                'footer_text' => 'Sistema de Avaliacao - Todos os direitos reservados',
                'maintenance_mode' => false,
                'allow_registration' => true,
                'max_file_size' => 2048,
                'session_timeout' => 120,
            ];
            
            return array_merge($defaults, $settings);
        });
    }
    
    /**
     * Definir uma configuração
     */
    private function setSetting($key, $value)
    {
        try {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        } catch (\Exception $e) {
            // Se a tabela não existir, não fazer nada
            // O sistema funcionará com valores padrão
        }
    }
    
    /**
     * Obter uma configuração específica
     */
    public static function getSetting($key, $default = null)
    {
        $settings = Cache::remember('system_settings', 3600, function () {
            try {
                $settings = DB::table('system_settings')->pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                // Se a tabela não existir, retornar array vazio
                $settings = [];
            }
            
            // Valores padrão
            $defaults = [
                'system_name' => 'Sistema de Avaliacao',
                'system_description' => 'Plataforma completa para criacao e gestao de avaliacoes educacionais',
                'logo_path' => null,
                'favicon_path' => null,
                'primary_color' => '#3B82F6',
                'secondary_color' => '#1F2937',
                'footer_text' => 'Sistema de Avaliacao - Todos os direitos reservados',
                'maintenance_mode' => false,
                'allow_registration' => true,
                'max_file_size' => 2048,
                'session_timeout' => 120,
            ];
            
            return array_merge($defaults, $settings);
        });
        
        return $settings[$key] ?? $default;
    }
}