<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['paginator', 'itemName' => 'itens']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['paginator', 'itemName' => 'itens']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php if($paginator->hasPages()): ?>
    <div class="mt-8">
        <!-- Informações da Paginação -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
            <div class="text-sm text-gray-700 bg-gray-50 px-4 py-2 rounded-lg">
                <span class="font-medium"><?php echo e($paginator->firstItem()); ?></span> - 
                <span class="font-medium"><?php echo e($paginator->lastItem()); ?></span> de 
                <span class="font-medium"><?php echo e($paginator->total()); ?></span> <?php echo e($itemName); ?>

            </div>
            <div class="text-sm text-gray-500 bg-blue-50 px-4 py-2 rounded-lg">
                📄 Página <?php echo e($paginator->currentPage()); ?> de <?php echo e($paginator->lastPage()); ?>

            </div>
        </div>
        
        <!-- Links de Paginação -->
        <div class="flex justify-center">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4">
                <?php echo e($paginator->appends(request()->query())->links('pagination.custom')); ?>

            </div>
        </div>
    </div>
<?php endif; ?><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/components/pagination-info.blade.php ENDPATH**/ ?>