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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Informações da paginação -->
                <div class="text-sm text-gray-700 order-2 sm:order-1">
                    <span class="font-medium"><?php echo e($paginator->firstItem()); ?></span>
                    a
                    <span class="font-medium"><?php echo e($paginator->lastItem()); ?></span>
                    de
                    <span class="font-medium"><?php echo e($paginator->total()); ?></span>
                    <?php echo e($itemName); ?>

                </div>
                
                <!-- Links de paginação -->
                <div class="order-1 sm:order-2">
                    <?php echo e($paginator->appends(request()->query())->links('pagination::tailwind')); ?>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?><?php /**PATH C:\Users\villa\Desktop\laragon\www\sisInscricao\resources\views/components/pagination-wrapper.blade.php ENDPATH**/ ?>