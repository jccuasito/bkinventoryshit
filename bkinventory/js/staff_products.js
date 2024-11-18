document.addEventListener('DOMContentLoaded', function () {
    const addProductBtn = document.getElementById('addProductBtn');
    const productModal = document.getElementById('productModal');
    const closeModalBtn = document.querySelector('.close');
    const productForm = document.getElementById('productForm');
    const editButtons = document.querySelectorAll('.edit-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const searchBar = document.getElementById('searchBar');
    const categorySort = document.getElementById('categorySort');
    const sizeSort = document.getElementById('sizeSort');
    const productTableBody = document.querySelector('#productTable tbody');
    const productImageInput = document.getElementById('product_image');
    const productImagePreview = document.getElementById('productImagePreview');

    // Function to preview image
    function previewImage(fileInput, imageElement) {
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            imageElement.src = e.target.result;
            imageElement.style.display = 'block';
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    // Open modal for adding product
    addProductBtn.addEventListener('click', function () {
        productForm.reset();
        document.getElementById('modalTitle').textContent = 'Add Product';
        productModal.style.display = 'flex'; // Ensure using flex for centering
        productForm.action = 'staff_add_product.php'; // Ensure the form action is set correctly
        productImagePreview.style.display = 'none'; // Hide the image preview when adding a new product
    });

    // Close modal
    closeModalBtn.addEventListener('click', function () {
        productModal.style.display = 'none';
    });

    // Close modal if clicked outside the content area
    window.addEventListener('click', function(event) {
        if (event.target == productModal) {
            productModal.style.display = 'none';
        }
    });

    // Open modal for editing product
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            productForm.reset();
            document.getElementById('product_id').value = this.getAttribute('data-id');
            document.getElementById('productName').value = this.getAttribute('data-name');
            document.getElementById('category').value = this.getAttribute('data-category');
            document.getElementById('size').value = this.getAttribute('data-size');
            document.getElementById('price').value = this.getAttribute('data-price');
            document.getElementById('invested_price').value = this.getAttribute('data-invested_price');
            document.getElementById('stock').value = this.getAttribute('data-stock');
            document.getElementById('cups').value = this.getAttribute('data-cups');
            document.getElementById('status').value = this.getAttribute('data-status');
            document.getElementById('modalTitle').textContent = 'Edit Product';
            productModal.style.display = 'flex'; // Ensure using flex for centering
            productForm.action = 'staff_edit_product.php';

            // Display the current product image if available
            const productImage = this.getAttribute('data-image');
            if (productImage) {
                productImagePreview.style.display = 'block';
                productImagePreview.src = productImage;
            } else {
                productImagePreview.style.display = 'none';
            }
        });
    });

    // Handle file input change event to preview image
    productImageInput.addEventListener('change', function() {
        previewImage(this, productImagePreview);
    });

    // Handle delete product
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            if (confirm('Are you sure you want to delete this product?')) {
                const productId = this.getAttribute('data-id');
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'staff_delete_product.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_id';
                input.value = productId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Filter products
    function filterProducts() {
        const searchQuery = searchBar.value.toLowerCase();
        const selectedCategory = categorySort.value;
        const selectedSize = sizeSort.value;

        const rows = productTableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const productCategory = row.querySelector('td:nth-child(3)').textContent;
            const productSize = row.querySelector('td:nth-child(4)').textContent;

            let matchesSearch = productName.includes(searchQuery);
            let matchesCategory = selectedCategory === '' || productCategory === selectedCategory;
            let matchesSize = selectedSize === '' || productSize === selectedSize;

            if (matchesSearch && matchesCategory && matchesSize) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Event listeners for search and sort
    searchBar.addEventListener('input', filterProducts);
    categorySort.addEventListener('change', filterProducts);
    sizeSort.addEventListener('change', filterProducts);
});
