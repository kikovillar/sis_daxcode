@props(['currentPerPage' => 15, 'route' => null])

<div class="flex items-center space-x-2 text-sm">
    <span class="text-gray-600">Itens por página:</span>
    <select onchange="changePerPage(this.value)" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
        <option value="10" {{ $currentPerPage == 10 ? 'selected' : '' }}>10</option>
        <option value="15" {{ $currentPerPage == 15 ? 'selected' : '' }}>15</option>
        <option value="25" {{ $currentPerPage == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ $currentPerPage == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ $currentPerPage == 100 ? 'selected' : '' }}>100</option>
    </select>
</div>

<script>
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
}
</script>