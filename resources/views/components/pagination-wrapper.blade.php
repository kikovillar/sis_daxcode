@props(['paginator', 'itemName' => 'itens'])

@if($paginator->hasPages())
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Informações da paginação -->
                <div class="text-sm text-gray-700 order-2 sm:order-1">
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {{ $itemName }}
                </div>
                
                <!-- Links de paginação -->
                <div class="order-1 sm:order-2">
                    {{ $paginator->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
@endif