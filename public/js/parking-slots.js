(() => {
    const slotDialog = document.getElementById('slot-dialog');
    const form = document.getElementById('slot-form');
    const deletion = document.getElementById('delete-slot-form');
    const title = document.getElementById('slot-dialog-title');
    let returnFocus;
    const show = dialog => { returnFocus = document.activeElement; dialog.showModal(); };
    function prepare(slot = null) {
        form.reset();
        form.action = slot ? slot.update_url : form.dataset.createUrl;
        form.elements._method.value = slot ? 'PUT' : 'POST';
        form.elements._slot_id.value = slot?.id || '';
        form.elements.zone_id.disabled = !!slot;
        title.textContent = slot ? `Edit Slot ${slot.slot_number}` : 'Add Single Slot';
        deletion.hidden = !slot;
        if (slot) {
            deletion.action = slot.delete_url;
            for (const [key, value] of Object.entries(slot)) {
                const input = form.elements.namedItem(key);
                if (!input) continue;
                if (input.tagName === 'SELECT' && value && ![...input.options].some(option => option.value === String(value))) input.add(new Option(value, value));
                input.value = value ?? '';
            }
        }
        show(slotDialog);
    }
    document.addEventListener('click', event => {
        const card = event.target.closest('[data-slot]');
        if (card) { prepare(JSON.parse(card.dataset.slot)); return; }
        if (event.target.closest('[data-create]')) { prepare(); return; }
        const trigger = event.target.closest('[data-open]');
        if (trigger && !trigger.disabled) show(document.getElementById(trigger.dataset.open));
        const close = event.target.closest('[data-close]');
        if (close) close.closest('dialog').close();
    });
    document.querySelectorAll('dialog').forEach(dialog => dialog.addEventListener('close', () => returnFocus?.focus()));
    deletion.addEventListener('submit', event => { if (!confirm('Delete this parking slot? This action cannot be undone.')) event.preventDefault(); });
    document.querySelector('.menu-toggle').addEventListener('click', event => {
        const open = document.getElementById('sidebar').classList.toggle('is-open');
        event.currentTarget.setAttribute('aria-expanded', String(open));
    });
    const filters = document.getElementById('slot-filters');
    filters.querySelectorAll('select').forEach(select => select.addEventListener('change', () => filters.requestSubmit()));
    let searchTimer;
    document.getElementById('slot-search').addEventListener('input', () => { clearTimeout(searchTimer); searchTimer = setTimeout(() => filters.requestSubmit(), 650); });
    function syncFloors(floors) {
        if (!Array.isArray(floors)) return;
        const select = filters.elements.floor;
        const selected = select.value;
        select.replaceChildren(new Option('Floor', ''));
        floors.forEach(floor => select.add(new Option(floor, floor)));
        if (selected && !floors.includes(selected)) select.add(new Option(selected, selected));
        select.value = selected;
    }
    let filterController;
    let filterRevision = 0;
    filters.addEventListener('submit', async event => {
        event.preventDefault();
        clearTimeout(searchTimer);
        filterController?.abort();
        const requestController = new AbortController(); filterController = requestController;
        const revision = ++filterRevision;
        const url = new URL(location.href);
        url.search = new URLSearchParams(new FormData(filters)).toString();
        const board = document.getElementById('slot-board');
        board.setAttribute('aria-busy', 'true');
        const timeout = setTimeout(() => requestController.abort(), 10000);
        try {
            const response = await fetch(url, {headers: {Accept: 'application/json'}, signal: requestController.signal});
            if (!response.ok) throw new Error('Filter request failed');
            const data = await response.json();
            if (revision !== filterRevision) return;
            syncFloors(data.floors);
            document.getElementById('slot-summary').innerHTML = data.summary;
            board.innerHTML = data.board;
            history.replaceState(null, '', url);
            document.getElementById('refresh-status').textContent = 'Live status - Filters updated';
        } catch (error) {
            if (revision === filterRevision) {
                document.getElementById('refresh-status').textContent = 'Unable to update filters. Please try again.';
            }
        } finally {
            clearTimeout(timeout);
            if (revision === filterRevision) board.removeAttribute('aria-busy');
        }
    });
    const oldNode = document.getElementById('slot-old-input');
    if (oldNode) {
        const old = JSON.parse(oldNode.textContent);
        if (old._dialog === 'slot-dialog') {
            const card = [...document.querySelectorAll('[data-slot]')].find(card => String(JSON.parse(card.dataset.slot).id) === old._slot_id);
            prepare(card ? JSON.parse(card.dataset.slot) : null);
        } else show(document.getElementById('batch-dialog'));
        const oldForm = document.querySelector(`#${old._dialog} form`);
        Object.entries(old).forEach(([key,value]) => { if (!key.startsWith('_') && oldForm.elements.namedItem(key)) oldForm.elements.namedItem(key).value = value ?? ''; });
    }
    let refreshing = false;
    setInterval(async () => {
        if (document.hidden || document.querySelector('dialog[open]') || document.activeElement?.closest('#slot-filters') || refreshing || document.getElementById('slot-board').hasAttribute('aria-busy')) return;
        refreshing = true; const revision = filterRevision;
        const activeSlot = document.activeElement?.dataset.slot ? JSON.parse(document.activeElement.dataset.slot).id : null;
        const controller = new AbortController(); const timeout = setTimeout(() => controller.abort(), 10000);
        try {
            const response = await fetch(location.href, {headers:{Accept:'application/json'}, signal:controller.signal});
            if (!response.ok) throw new Error('Refresh failed');
            const data = await response.json();
            if (revision !== filterRevision) return;
            syncFloors(data.floors);
            document.getElementById('slot-summary').innerHTML = data.summary;
            document.getElementById('slot-board').innerHTML = data.board;
            if (activeSlot) [...document.querySelectorAll('[data-slot]')].find(card => JSON.parse(card.dataset.slot).id === activeSlot)?.focus({preventScroll:true});
            document.getElementById('refresh-status').textContent = 'Live status · Updated ' + new Date().toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
        } catch { document.getElementById('refresh-status').textContent = 'Live update unavailable. Retrying automatically.'; }
        finally { clearTimeout(timeout); refreshing = false; }
    }, 20000);
})();
