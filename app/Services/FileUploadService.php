<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileUploadService
{
    /**
     * Upload multiple files for a user
     */
    public function uploadUserFiles(array $files, array $fileFields): array
    {
        $uploadedFiles = [];
        
        foreach ($fileFields as $field) {
            if (isset($files[$field]) && $files[$field] instanceof UploadedFile) {
                $file = $files[$field];
                
                // Skip if file is essentially empty or invalid
                if (!$file->isValid()) {
                    continue;
                }
                
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                
                // Skip files with no name, no size, or suspicious characteristics
                if (empty($originalName) || $fileSize <= 0 || $originalName === 'blob') {
                    continue;
                }
                
                // Additional extension check
                $extension = $file->getClientOriginalExtension();
                if (empty($extension)) {
                    // Try to extract from original name
                    if (strpos($originalName, '.') !== false) {
                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                    }
                    if (empty($extension)) {
                        continue; // Skip files without extension
                    }
                }
                
                try {
                    $uploadedFiles[$field] = $this->uploadFile($file, $field, 'documents');
                } catch (\Exception $e) {
                    Log::warning("Failed to upload file for field {$field}: " . $e->getMessage());
                    // Continue processing other files instead of failing completely
                }
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Upload profile photo
     */
    public function uploadProfilePhoto(UploadedFile $file, ?int $userId = null): string
    {
        // Comprehensive validation before processing
        if (!$file || 
            !$file->isValid() || 
            $file->getSize() === 0 || 
            empty($file->getClientOriginalName()) ||
            empty($file->getClientOriginalExtension())) {
            throw new \Exception("Invalid file: file is empty, invalid, or has no name/extension");
        }
        
        $suffix = $userId ? "_user_{$userId}" : '_new';
        return $this->uploadFile($file, 'profile' . $suffix, 'profile_photos');
    }

    /**
     * Upload a single file
     */
    private function uploadFile(UploadedFile $file, string $prefix, string $directory): string
    {
        try {
            // Validate inputs
            if (empty($directory)) {
                throw new \Exception("Directory cannot be empty");
            }
            
            if (empty($prefix)) {
                $prefix = 'file';
            }
            
            // Ensure directory exists
            $fullPath = storage_path("app/public/{$directory}");
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Generate unique filename with proper extension
            $extension = $file->getClientOriginalExtension();
            if (empty($extension)) {
                // Try to get extension from original name
                $originalName = $file->getClientOriginalName();
                if ($originalName && strpos($originalName, '.') !== false) {
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                }
                if (empty($extension)) {
                    $extension = 'tmp';
                }
            }
            
            $filename = time() . '_' . $prefix . '_' . uniqid() . '.' . $extension;
            
            // Validate filename
            if (empty($filename) || strlen($filename) < 5) {
                throw new \Exception("Generated filename is empty or too short: {$filename}");
            }
            
            // Log debug information
            Log::info("Attempting file upload", [
                'directory' => $directory,
                'filename' => $filename,
                'prefix' => $prefix,
                'file_size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
                'extension' => $extension
            ]);
            
            // Final validation before storage
            if (empty($directory) || empty($filename)) {
                throw new \Exception("Directory or filename is empty - Directory: '{$directory}', Filename: '{$filename}'");
            }
            
            // Store the file
            $path = $file->storeAs($directory, $filename, 'public');
            
            if (empty($path)) {
                throw new \Exception("File storage returned empty path");
            }
            
            Log::info("File uploaded successfully: {$prefix} -> {$path}");
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error("File upload error for {$prefix}: " . $e->getMessage());
            throw new \Exception("Erro ao fazer upload do arquivo {$prefix}: " . $e->getMessage());
        }
    }

    /**
     * Delete old files when updating
     */
    public function deleteOldFiles(array $oldFiles, array $fileFields): void
    {
        foreach ($fileFields as $field) {
            if (isset($oldFiles[$field]) && !empty($oldFiles[$field])) {
                $this->deleteFile($oldFiles[$field]);
            }
        }
    }

    /**
     * Delete a single file
     */
    public function deleteFile(string $filePath): void
    {
        try {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                Log::info("File deleted successfully: {$filePath}");
            }
        } catch (\Exception $e) {
            Log::error("File deletion error for {$filePath}: " . $e->getMessage());
        }
    }

    /**
     * Get the list of document file fields
     */
    public function getDocumentFields(): array
    {
        return ['documento_rg', 'documento_cnh', 'documento_cpf', 'documento_foto_3x4'];
    }
}