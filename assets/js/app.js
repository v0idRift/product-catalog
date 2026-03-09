/* global initialData, bootstrap */

document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const productList = document.getElementById('product-list');
    const categoryItems = document.querySelectorAll('.category-item');
    const sortSelect = document.getElementById('sort-select');
    const buyModal = new bootstrap.Modal(document.getElementById('buyModal'));
    const buyModalBody = document.getElementById('buyModalBody');

    let currentCategoryId = initialData.filters.category_id ?? '';
    let currentSort = initialData.filters.sort || 'date_desc';

    // Render initial products from embedded JSON
    renderProducts(initialData.products);

    // Category click
    categoryItems.forEach(item => {
        item.addEventListener('click', () => {
            currentCategoryId = item.dataset.categoryId;
            setActiveCategory(currentCategoryId);
            fetchAndRender();
        });
    });

    // Sort change
    sortSelect.addEventListener('change', () => {
        currentSort = sortSelect.value;
        fetchAndRender();
    });

    // Product card "buy" button delegation
    productList.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-buy');
        if (!btn) return;

        const card = btn.closest('.product-card');
        const name = card.dataset.name;
        const price = card.dataset.price;
        const date = card.dataset.date;
        const category = card.dataset.category;

        buyModalBody.innerHTML = `
            <div class="modal-detail-row">
                <span class="modal-detail-label">Назва:</span>
                <span class="modal-detail-value">${escapeHtml(name)}</span>
            </div>
            <div class="modal-detail-row">
                <span class="modal-detail-label">Ціна:</span>
                <span class="modal-detail-value price">${formatPrice(price)} грн</span>
            </div>
            <div class="modal-detail-row">
                <span class="modal-detail-label">Категорія:</span>
                <span class="modal-detail-value category">${escapeHtml(category)}</span>
            </div>
            <div class="modal-detail-row">
                <span class="modal-detail-label">Дата додавання:</span>
                <span class="modal-detail-value">${formatDate(date)}</span>
            </div>
            <div class="modal-info-alert">
                <i class="bi bi-info-circle me-1"></i>
                Натискаючи «Підтвердити», товар буде додано до вашого кошика.
            </div>
        `;
        buyModal.show();
    });

    function fetchAndRender() {
        const params = new URLSearchParams();
        if (currentCategoryId !== '') {
            params.set('category_id', currentCategoryId);
        }
        params.set('sort', currentSort);

        // Update URL without reload
        const newUrl = window.location.pathname + '?' + params.toString();
        history.pushState(null, '', newUrl);

        fetch('api/products.php?' + params.toString())
            .then(res => res.json())
            .then(data => renderProducts(data.products))
            .catch(err => console.error('Fetch error:', err));
    }

    function renderProducts(products) {
        if (!products || products.length === 0) {
            productList.innerHTML = '<div class="col-12"><p class="text-muted">Товарів не знайдено.</p></div>';
            return;
        }

        productList.innerHTML = products.map(p => `
            <div class="col-md-4">
                <div class="card product-card"
                     data-name="${escapeAttr(p.name)}"
                     data-price="${escapeAttr(p.price)}"
                     data-date="${escapeAttr(p.created_at)}"
                     data-category="${escapeAttr(p.category_name)}">
                    <div class="card-body">
                        <span class="category-badge">${escapeHtml(p.category_name)}</span>
                        <h6 class="card-title">${escapeHtml(p.name)}</h6>
                        <div class="price">${formatPrice(p.price)} грн</div>
                        <div class="date">Додано: ${formatDate(p.created_at)}</div>
                        <button class="btn btn-primary btn-buy"><i class="bi bi-cart-plus me-1"></i>Купити</button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function setActiveCategory(id) {
        categoryItems.forEach(item => {
            item.classList.toggle('active', item.dataset.categoryId === String(id));
        });
    }

    function formatPrice(price) {
        return parseFloat(price).toLocaleString('uk-UA', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('uk-UA');
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function escapeAttr(str) {
        return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Handle browser back/forward
    window.addEventListener('popstate', () => {
        const params = new URLSearchParams(window.location.search);
        currentCategoryId = params.get('category_id') || '';
        currentSort = params.get('sort') || 'date_desc';
        sortSelect.value = currentSort;
        setActiveCategory(currentCategoryId);

        fetch('api/products.php?' + params.toString())
            .then(res => res.json())
            .then(data => renderProducts(data.products))
            .catch(err => console.error('Fetch error:', err));
    });
});
