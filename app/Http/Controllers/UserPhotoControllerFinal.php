<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        try {
            // Verificar se o arquivo foi enviado
            if (!$request->hasFile('profile_photo')) {
                return back()->with('error', 'Nenhum arquivo foi selecionado.');
            }

            $file = $request->file('profile_photo');
            
            // Verificar se o arquivo é válido
            if (!$file->isValid()) {
                return back()->with('error', 'Arquivo inválido: ' . $file->getErrorMessage());
            }

            // Criar diretório se não existir
            $uploadDir = storage_path('app/public/profile_photos');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Remover foto anterior se existir
            if ($user->profile_photo && !empty($user->profile_photo)) {
                $oldPhotoPath = storage_path('app/public/' . $user->profile_photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            // Gerar nome único para o arquivo
            $extension = strtolower($file->getClientOriginalExtension());
            if (empty($extension) || !in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $extension = 'jpg';
            }

            $filename = 'user_' . $user->id . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

            // Usar move() em vez de file_get_contents() para evitar problemas de path
            $moved = $file->move($uploadDir, $filename);
            
            if (!$moved) {
                return back()->with('error', 'Erro ao mover o arquivo.');
            }

            // Verificar se o arquivo foi realmente salvo
            if (!file_exists($targetPath)) {
                return back()->with('error', 'Arquivo não foi salvo corretamente.');
            }

            // Atualizar usuário
            $relativePath = 'profile_photos/' . $filename;
            $user->update(['profile_photo' => $relativePath]);

            return back()->with('success', 'Foto de perfil atualizada com sucesso!');

        } catch (\Exception $e) {
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
            if ($user->profile_photo && !empty($user->profile_photo)) {
                $photoPath = storage_path('app/public/' . $user->profile_photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
                $user->update(['profile_photo' => null]);
            }

            return back()->with('success', 'Foto de perfil removida com sucesso!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover foto: ' . $e->getMessage());
        }
    }
}