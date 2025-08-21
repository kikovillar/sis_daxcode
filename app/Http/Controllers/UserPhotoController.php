<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserPhotoController extends Controller
{
    /**
     * Upload ou atualizar foto de perfil do usuário
     */
    public function upload(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        $request->validate([
            'profile_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ], [
            'profile_photo.required' => 'Por favor, selecione uma foto.',
            'profile_photo.file' => 'O arquivo deve ser uma imagem válida.',
            'profile_photo.mimes' => 'A foto deve ser JPG, JPEG ou PNG.',
            'profile_photo.max' => 'A foto deve ter no máximo 2MB.'
        ]);

        try {
            \Log::info('=== INÍCIO DO UPLOAD ===', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);

            // Verificar se o arquivo foi enviado
            if (!$request->hasFile('profile_photo')) {
                \Log::error('Nenhum arquivo foi enviado');
                return back()->with('error', 'Nenhum arquivo foi selecionado.');
            }

            $file = $request->file('profile_photo');
            
            // Informações detalhadas do arquivo
            \Log::info('Arquivo recebido', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'real_path' => $file->getRealPath(),
                'is_valid' => $file->isValid()
            ]);
            
            // Verificar se o arquivo é válido
            if (!$file->isValid()) {
                $error = $file->getErrorMessage();
                \Log::error('Arquivo inválido', ['error' => $error]);
                return back()->with('error', 'Arquivo inválido: ' . $error);
            }

            // Remover foto anterior se existir
            if ($user->profile_photo && !empty($user->profile_photo)) {
                if (Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                    \Log::info('Foto anterior removida: ' . $user->profile_photo);
                }
            }

            // Criar diretório se não existir
            $profilePhotosPath = storage_path('app/public/profile_photos');
            if (!is_dir($profilePhotosPath)) {
                mkdir($profilePhotosPath, 0755, true);
                \Log::info('Diretório profile_photos criado');
            }

            // Gerar nome único e seguro para o arquivo
            $extension = strtolower($file->getClientOriginalExtension());
            if (empty($extension)) {
                // Tentar detectar extensão pelo MIME type
                $mimeType = $file->getMimeType();
                switch ($mimeType) {
                    case 'image/jpeg':
                        $extension = 'jpg';
                        break;
                    case 'image/png':
                        $extension = 'png';
                        break;
                    default:
                        $extension = 'jpg';
                }
            }

            // Garantir que a extensão é válida
            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $extension = 'jpg';
            }

            $filename = 'user_' . $user->id . '_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            \Log::info('Nome do arquivo gerado', [
                'filename' => $filename,
                'extension' => $extension
            ]);

            // Verificar se filename não está vazio
            if (empty($filename)) {
                \Log::error('Nome do arquivo está vazio');
                return back()->with('error', 'Erro ao gerar nome do arquivo.');
            }

            // Salvar arquivo usando método direto (evitando storeAs problemático)
            $destinationPath = storage_path('app/public/profile_photos');
            $targetPath = $destinationPath . DIRECTORY_SEPARATOR . $filename;
            $path = null;
            
            \Log::info('Tentando salvar arquivo', [
                'destination_path' => $destinationPath,
                'target_path' => $targetPath,
                'filename' => $filename
            ]);
            
            // Método 1: move() - mais confiável
            try {
                $moved = $file->move($destinationPath, $filename);
                if ($moved) {
                    $path = 'profile_photos/' . $filename;
                    \Log::info('Arquivo salvo com move: ' . $path);
                }
            } catch (\Exception $e) {
                \Log::warning('move falhou, tentando copy', ['error' => $e->getMessage()]);
                
                // Método 2: copy() - fallback
                try {
                    $sourcePath = $file->getRealPath();
                    if (copy($sourcePath, $targetPath)) {
                        $path = 'profile_photos/' . $filename;
                        \Log::info('Arquivo salvo com copy: ' . $path);
                    }
                } catch (\Exception $e2) {
                    \Log::error('copy também falhou', ['error' => $e2->getMessage()]);
                    
                    // Método 3: file_put_contents() - último recurso
                    try {
                        $content = file_get_contents($file->getRealPath());
                        if (file_put_contents($targetPath, $content)) {
                            $path = 'profile_photos/' . $filename;
                            \Log::info('Arquivo salvo com file_put_contents: ' . $path);
                        }
                    } catch (\Exception $e3) {
                        \Log::error('file_put_contents também falhou', ['error' => $e3->getMessage()]);
                        throw new \Exception('Todos os métodos de salvamento falharam');
                    }
                }
            }
            
            if (!$path) {
                \Log::error('Falha ao salvar arquivo - path vazio');
                return back()->with('error', 'Erro ao salvar o arquivo.');
            }

            // Verificar se o arquivo foi realmente salvo
            $fullPath = storage_path('app/public/' . $path);
            if (!file_exists($fullPath)) {
                \Log::error('Arquivo não foi salvo', ['expected_path' => $fullPath]);
                return back()->with('error', 'Arquivo não foi salvo corretamente.');
            }

            // Atualizar usuário
            $updated = $user->update(['profile_photo' => $path]);
            
            \Log::info('=== UPLOAD CONCLUÍDO ===', [
                'updated' => $updated,
                'new_profile_photo' => $path,
                'file_size' => filesize($fullPath)
            ]);

            return back()->with('success', 'Foto de perfil atualizada com sucesso! (' . $filename . ')');

        } catch (\Exception $e) {
            \Log::error('=== ERRO NO UPLOAD ===', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erro ao fazer upload da foto: ' . $e->getMessage());
        }
    }

    /**
     * Remover foto de perfil do usuário
     */
    public function remove(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        try {
            $user->removeProfilePhoto();
            return back()->with('success', 'Foto de perfil removida com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao remover foto', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao remover foto: ' . $e->getMessage());
        }
    }
}