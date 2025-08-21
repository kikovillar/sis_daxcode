<?php

namespace App\Services;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserCreationService
{
    public function __construct(
        private FileUploadService $fileUploadService
    ) {}

    /**
     * Create a new user with all data and files
     */
    public function createUser(StoreUserRequest $request): User
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validated();
            
            // Convert education radio buttons to boolean fields
            $validated = $this->convertEducationFields($validated);
            
            // Handle disponibilidade_dias JSON conversion
            if (isset($validated['disponibilidade_dias']) && is_array($validated['disponibilidade_dias'])) {
                $validated['disponibilidade_dias'] = json_encode($validated['disponibilidade_dias']);
            }
            
            // Handle file uploads
            $uploadedFiles = $this->handleFileUploads($request);
            $validated = array_merge($validated, $uploadedFiles);
            
            // Hash password and set email verification
            $validated['password'] = Hash::make($validated['password']);
            $validated['email_verified_at'] = now();
            
            return User::create($validated);
        });
    }

    /**
     * Update an existing user with all data and files
     */
    public function updateUser(UpdateUserRequest $request, User $user): User
    {
        return DB::transaction(function () use ($request, $user) {
            $validated = $request->validated();
            
            // Convert education radio buttons to boolean fields
            $validated = $this->convertEducationFields($validated);
            
            // Handle disponibilidade_dias JSON conversion
            if (isset($validated['disponibilidade_dias']) && is_array($validated['disponibilidade_dias'])) {
                $validated['disponibilidade_dias'] = json_encode($validated['disponibilidade_dias']);
            }
            
            // Handle file uploads and deletions
            $uploadedFiles = $this->handleFileUploads($request, $user);
            $validated = array_merge($validated, $uploadedFiles);
            
            // Handle password update
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            
            $user->update($validated);
            
            return $user->fresh();
        });
    }

    /**
     * Handle file uploads for user creation/update
     */
    private function handleFileUploads($request, ?User $user = null): array
    {
        $uploadedFiles = [];
        
        try {
            // Only process files if there are actual valid files to upload
            $hasValidFiles = false;
            
            // Check for valid profile photo
            if ($request->hasFile('profile_photo')) {
                $profilePhoto = $request->file('profile_photo');
                if ($this->isValidUploadFile($profilePhoto)) {
                    $hasValidFiles = true;
                    
                    // Delete old profile photo if updating
                    if ($user && $user->profile_photo) {
                        $this->fileUploadService->deleteFile($user->profile_photo);
                    }
                    
                    $uploadedFiles['profile_photo'] = $this->fileUploadService->uploadProfilePhoto(
                        $profilePhoto,
                        $user?->id
                    );
                }
            }
            
            // Check for valid document files
            $fileFields = $this->fileUploadService->getDocumentFields();
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    if ($this->isValidUploadFile($file)) {
                        $hasValidFiles = true;
                        
                        $singleFileArray = [$field => $file];
                        $result = $this->fileUploadService->uploadUserFiles($singleFileArray, [$field]);
                        if (!empty($result[$field])) {
                            $uploadedFiles[$field] = $result[$field];
                        }
                    }
                }
            }
            
            // If no valid files were found, return empty array
            if (!$hasValidFiles) {
                return [];
            }
            
        } catch (\Exception $e) {
            \Log::error("File upload error: " . $e->getMessage());
            // Return empty array on any file upload error to prevent breaking the update
            return [];
        }
        
        return $uploadedFiles;
    }

    /**
     * Check if uploaded file is valid for processing
     */
    private function isValidUploadFile($file): bool
    {
        if (!$file || !$file->isValid()) {
            return false;
        }
        
        $originalName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        
        // Reject files with problematic characteristics
        if (empty($originalName) || 
            $fileSize <= 0 || 
            $originalName === 'blob' ||
            strlen($originalName) < 3) {
            return false;
        }
        
        // Check for valid extension
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            if (strpos($originalName, '.') !== false) {
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            }
            if (empty($extension)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Convert education radio button fields to boolean fields for database storage
     */
    private function convertEducationFields(array $validated): array
    {
        $educationMappings = [
            'ensino_fundamental_status' => [
                'concluido_field' => 'ensino_fundamental_concluido',
                'cursando_field' => 'ensino_fundamental_cursando'
            ],
            'ensino_medio_status' => [
                'concluido_field' => 'ensino_medio_concluido',
                'cursando_field' => 'ensino_medio_cursando'
            ],
            'ensino_tecnico_status' => [
                'concluido_field' => 'ensino_tecnico_concluido',
                'cursando_field' => 'ensino_tecnico_cursando'
            ],
            'ensino_superior_status' => [
                'concluido_field' => 'ensino_superior_concluido',
                'cursando_field' => 'superior_cursando',
                'trancado_field' => 'superior_trancado'
            ],
            'pos_graduacao_status' => [
                'concluido_field' => 'pos_graduacao_concluido',
                'cursando_field' => 'pos_graduacao_cursando'
            ]
        ];

        foreach ($educationMappings as $statusField => $mapping) {
            if (isset($validated[$statusField])) {
                $status = $validated[$statusField];
                
                // Reset all related fields to false
                $validated[$mapping['concluido_field']] = false;
                $validated[$mapping['cursando_field']] = false;
                if (isset($mapping['trancado_field'])) {
                    $validated[$mapping['trancado_field']] = false;
                }
                
                // Set the appropriate field to true based on status
                switch ($status) {
                    case 'concluido':
                        $validated[$mapping['concluido_field']] = true;
                        break;
                    case 'cursando':
                        $validated[$mapping['cursando_field']] = true;
                        break;
                    case 'trancado':
                        if (isset($mapping['trancado_field'])) {
                            $validated[$mapping['trancado_field']] = true;
                        }
                        break;
                    case 'nao':
                    default:
                        // All fields remain false
                        break;
                }
                
                // Remove the status field as it's not needed in the database
                unset($validated[$statusField]);
            }
        }

        return $validated;
    }

    /**
     * Prepare user data for display in views
     */
    public function prepareUserDisplayData(User $user): array
    {
        return [
            'dados_basicos' => [
                'Nome Completo' => $user->name ?: 'Não informado',
                'Email' => $user->email ?: 'Não informado',
                'Tipo de Usuário' => $this->formatUserRole($user->role),
                'Status' => $user->email_verified_at ? 'Ativo' : 'Inativo',
                'Cadastrado em' => $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'Não informado',
            ],
            'dados_pessoais' => [
                'Data de Nascimento' => $user->data_nascimento ? $user->data_nascimento->format('d/m/Y') : 'Não informado',
                'Idade' => $user->idade ? $user->idade . ' anos' : 'Não informado',
                'Sexo' => $user->sexo ? ucfirst($user->sexo) : 'Não informado',
                'RG' => $user->rg ?: 'Não informado',
                'CPF' => $user->cpf ?: 'Não informado',
                'Telefone' => $user->telefone ?: 'Não informado',
            ],
            'endereco' => [
                'Endereço' => $user->endereco ?: 'Não informado',
                'Cidade' => $user->cidade ?: 'Não informado',
                'Estado' => $user->estado ?: 'Não informado',
            ],
            'educacao' => [
                'Ensino Fundamental' => $this->formatEducationStatus($user, 'fundamental'),
                'Ensino Médio' => $this->formatEducationStatus($user, 'medio'),
                'Ensino Superior' => $this->formatEducationStatus($user, 'superior'),
            ]
        ];
    }

    private function formatUserRole(string $role): string
    {
        return match($role) {
            'admin' => 'Administrador',
            'professor' => 'Professor',
            'aluno' => 'Aluno',
            default => ucfirst($role)
        };
    }

    private function formatEducationStatus(User $user, string $level): string
    {
        $mapping = [
            'fundamental' => ['concluido' => 'ensino_fundamental_concluido', 'cursando' => 'ensino_fundamental_cursando'],
            'medio' => ['concluido' => 'ensino_medio_concluido', 'cursando' => 'ensino_medio_cursando'],
            'superior' => ['concluido' => 'ensino_superior_concluido', 'cursando' => 'superior_cursando', 'trancado' => 'superior_trancado'],
        ];

        if (!isset($mapping[$level])) {
            return 'Não informado';
        }

        $fields = $mapping[$level];
        
        if (isset($fields['concluido']) && $user->{$fields['concluido']}) {
            return 'Concluído';
        } elseif (isset($fields['cursando']) && $user->{$fields['cursando']}) {
            return 'Cursando';
        } elseif (isset($fields['trancado']) && $user->{$fields['trancado']}) {
            return 'Trancado';
        }
        
        return 'Não cursou';
    }
}