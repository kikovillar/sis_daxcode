@props(['paginator', 'itemName' => 'itens'])

@if($paginator->hasPages())
    <div class="mt-8">
        <!-- Informações da Paginação -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
            <div class="text-sm text-gray-700 bg-gray-50 px-4 py-2 rounded-lg">
                <span class="font-medium">{{ $paginator->firstItem() }}</span> - 
                <span class="font-medium">{{ $paginator->lastItem() }}</span> de 
                <span class="font-medium">{{ $paginator->total() }}</span> {{ $itemName }}
            </div>
            <div class="text-sm text-gray-500 bg-blue-50 px-4 py-2 rounded-lg">
                📄 Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
            </div>
        </div>
        
        <!-- Links de Paginação -->
        <div class="flex justify-center">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                {{ $paginator->appends(request()->query())->links('pagination.custom') }}
            </div>
        </div>
    </div>
@endif