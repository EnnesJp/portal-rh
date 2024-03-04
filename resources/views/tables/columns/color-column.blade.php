<div class="px-4 py-3">
    <!--<div
        x-data="{}"
        x-tooltip.raw="{{ $getState() }}"
        class="h-5 w-5 rounded"
        style="background-color: {{ $getState() }}"
    ></div>-->

    <div
        x-data="{
            state: @js($getState()),
        }"
        x-init="
            $watch('state', () => {
                $wire.updateTableColumnState(
                    @js($getName()),
                    @js($recordKey),
                    state
                );
            })
        "
    >
        <input
            x-model.lazy="state"
            class="rounded"
            type="color"
        />
    </div>
</div>
