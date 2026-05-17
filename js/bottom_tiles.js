(function () {
    function readHidden(storageKey) {
        try {
            var value = window.localStorage.getItem(storageKey);
            var items = value ? JSON.parse(value) : [];
            return Array.isArray(items) ? items : [];
        } catch (e) {
            return [];
        }
    }

    function saveHidden(storageKey, items) {
        try {
            window.localStorage.setItem(storageKey, JSON.stringify(items));
        } catch (e) {}
    }

    function getTileLabel(tile, index) {
        var titledChild = tile.querySelector('[title]');
        return tile.getAttribute('title') ||
            tile.getAttribute('aria-label') ||
            (titledChild ? titledChild.getAttribute('title') : '') ||
            tile.textContent.replace(/\s+/g, ' ').trim() ||
            'Плитка ' + (index + 1);
    }

    function getTileId(tile, index, counts) {
        var base = tile.getAttribute('href') ||
            tile.getAttribute('onclick') ||
            tile.getAttribute('title') ||
            getTileLabel(tile, index) ||
            String(index);
        counts[base] = (counts[base] || 0) + 1;
        return counts[base] > 1 ? base + '#' + counts[base] : base;
    }

    var settings = document.getElementById('savoirBottomTileSettings');
    var state = document.getElementById('savoirBottomTilePanelState');
    var toggle = document.getElementById('savoirBottomTileToggle');
    var panel = document.getElementById('savoirBottomTilePanel');
    var list = document.getElementById('savoirBottomTileList');
    var reset = document.getElementById('savoirBottomTileReset');

    if (!settings || !state || !toggle || !panel || !list || !reset) {
        return;
    }

    var storageKey = settings.getAttribute('data-storage-key') || 'savoirBottomTilesHidden';
    var hidden = readHidden(storageKey);
    var tiles = Array.prototype.slice.call(document.querySelectorAll(
        '.savoir-bottom-navbar .savoir-status-nav a.macintosh,' +
        '.savoir-bottom-navbar .savoir-status-nav a.macintoshred,' +
        '.savoir-bottom-navbar .savoir-status-nav a.macintoshgavno'
    )).filter(function (tile) {
        return !tile.className.match(/\bdob_kar\b/);
    });
    var counts = {};

    function syncHasHiddenState() {
        settings.classList.toggle('has-hidden-tiles', hidden.length > 0);
    }

    tiles.forEach(function (tile, index) {
        tile.setAttribute('data-savoir-tile-id', getTileId(tile, index, counts));
        tile.setAttribute('data-savoir-tile-label', getTileLabel(tile, index));
    });

    function applyVisibility() {
        tiles.forEach(function (tile) {
            var isHidden = hidden.indexOf(tile.getAttribute('data-savoir-tile-id')) !== -1;
            tile.classList.toggle('savoir-bottom-tile-hidden', isHidden);
        });
        saveHidden(storageKey, hidden);
        syncHasHiddenState();
    }

    function setTileVisible(tileId, visible) {
        var currentIndex = hidden.indexOf(tileId);
        if (visible && currentIndex !== -1) {
            hidden.splice(currentIndex, 1);
        }
        if (!visible && currentIndex === -1) {
            hidden.push(tileId);
        }
        applyVisibility();
    }

    function renderList() {
        list.innerHTML = '';

        if (!tiles.length) {
            var empty = document.createElement('div');
            empty.className = 'savoir-bottom-tile-empty';
            empty.textContent = 'Нет доступных плиток';
            list.appendChild(empty);
            return;
        }

        tiles.forEach(function (tile) {
            var tileId = tile.getAttribute('data-savoir-tile-id');
            var item = document.createElement('label');
            var input = document.createElement('input');
            var label = document.createElement('span');

            item.className = 'savoir-bottom-tile-item';
            input.type = 'checkbox';
            input.checked = hidden.indexOf(tileId) === -1;
            label.textContent = tile.getAttribute('data-savoir-tile-label');

            input.addEventListener('change', function () {
                setTileVisible(tileId, input.checked);
            });

            item.appendChild(input);
            item.appendChild(label);
            list.appendChild(item);
        });
    }

    function syncPanelState() {
        var isOpen = state.checked;
        settings.classList.toggle('is-open', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');

        if (isOpen) {
            renderList();
        }
    }

    state.addEventListener('change', syncPanelState);

    toggle.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            state.checked = !state.checked;
            syncPanelState();
        }
    });

    reset.addEventListener('click', function () {
        hidden = [];
        applyVisibility();
        renderList();
    });

    document.addEventListener('click', function (event) {
        if (!settings.contains(event.target)) {
            state.checked = false;
            syncPanelState();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            state.checked = false;
            syncPanelState();
        }
    });

    renderList();
    applyVisibility();
    syncHasHiddenState();
    syncPanelState();
})();
