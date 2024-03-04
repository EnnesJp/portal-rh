<div {{ $attributes->class(['divide-y border shadow-sm rounded-lg']) }}>
    <div class="p-6 flex gap-3">
        @if ($icon = $getIcon())
            @svg($icon, 'w-7 h-7 text-gray-500')
        @endif

        <div class="flex-1 space-y-2">
            <h2 class="text-2xl font-bold">
                {{ $getHeading() }}
            </h2>

            <p class="text-sm text-gray-700">
                {{ $getDescription() }}
            </p>
        </div>
    </div>

    <div class="p-6">
        {{ $getChildComponentContainer() }}
    </div>
</div>
