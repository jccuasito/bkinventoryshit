document.addEventListener('DOMContentLoaded', function () {
    const searchBar = document.getElementById('searchBar');
    const categorySort = document.getElementById('categorySort');
    const sizeSort = document.getElementById('sizeSort');
    const productTableBody = document.querySelector('#productTable tbody');
    const totalValue = document.getElementById('totalValue');
    const addToCartButton = document.querySelector('.add-to-cart');
    const modal = document.createElement('div');
    modal.classList.add('modal');
    document.body.appendChild(modal);

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

    function calculateTotal() {
        const rows = productTableBody.querySelectorAll('tr');
        let grandTotal = 0;

        rows.forEach(row => {
            const price = parseFloat(row.querySelector('td:nth-child(5)').textContent); // Product price
            const status = row.querySelector('td:nth-child(6) .status').textContent.trim().toLowerCase(); // Product status
            const quantityCell = row.querySelector('td:nth-child(7)');
            const totalCell = row.querySelector('td:nth-child(8)');
            let quantity = 0;

            if (status === 'available') {
                if (quantityCell.querySelector('select')) {
                    quantity = parseInt(quantityCell.querySelector('select').value);
                }
            }

            const rowTotal = price * quantity;
            totalCell.textContent = rowTotal.toFixed(2);

            grandTotal += rowTotal;
        });

        totalValue.textContent = grandTotal.toFixed(2);
    }

    // Event listeners for search and sort
    searchBar.addEventListener('input', filterProducts);
    categorySort.addEventListener('change', filterProducts);
    sizeSort.addEventListener('change', filterProducts);

    // Initial calculation of total
    calculateTotal();

    // Calculate total on quantity change
    productTableBody.addEventListener('change', (event) => {
        if (event.target.tagName === 'SELECT') {
            calculateTotal();
        }
    });

    addToCartButton.addEventListener('click', showModal);
});

document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById("receiptModal");
    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {
        modal.style.display = "none";
        location.reload(); // Reload the page when the modal is closed
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            location.reload(); // Reload the page when clicking outside the modal
        }
    }

    // Function to show the receipt modal
    function showReceiptModal(receiptHTML) {
        document.getElementById("receiptDetails").innerHTML = receiptHTML;
        modal.style.display = "block"; // Show the modal
    }

    // Add event listener for the checkout button
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "staff_checkout_process.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                if (response.success) {
                    alert(response.message); // Show order successful message
                    showReceiptModal(response.receiptHTML); // Show receipt modal

                    // Set the response data to be used in the CSV download
                    window.receiptData = {
                        totalAmount: response.total_amount,
                        amountPaid: response.customer_paid,
                        paymentMethod: response.payment_method,
                        changeAmount: response.customer_paid - response.total_amount,
                        cashierName: response.cashier_name
                    };
                } else {
                    alert(response.message);
                }
            }
        };

        var formData = new FormData(document.getElementById('checkoutForm'));
        xhr.send(new URLSearchParams(formData).toString());
    });

    // Add event listener for the print receipt button
    document.getElementById('printReceipt').addEventListener('click', function () {
        var receiptContent = document.getElementById('receiptDetails').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = receiptContent;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Reload the page after printing
    });

    // Add event listener for the download Excel button
    document.getElementById('downloadExcel').addEventListener('click', function () {
        if (!window.receiptData) {
            alert("No receipt data available.");
            return;
        }

        var csvContent = "data:text/csv;charset=utf-8,";
        
        // Add CSV header
        csvContent += "Product,Quantity,Price,Total Amount,Amount Paid,Payment Method,Change Amount,Cashier Name\r\n";

        // Get the product details from the receipt and format each product in CSV rows
        var table = document.getElementById('receiptDetails').querySelector('table');
        var rows = table.querySelectorAll('tr');

        rows.forEach(function (row, index) {
            if (index === 0) return; // Skip the header row

            var cols = row.querySelectorAll('td');
            if (cols.length > 0) {
                var product = cols[0].textContent.trim();    // Product name
                var quantity = parseInt(cols[1].textContent.trim(), 10);   // Quantity
                var price = parseFloat(cols[2].textContent.trim());      // Price
                var totalAmount = (price * quantity).toFixed(2);  // Total Amount (Price * Quantity)

                // Format the row data and append it to the CSV content
                csvContent += `${product},${quantity},${price.toFixed(2)},${totalAmount},${window.receiptData.amountPaid},${window.receiptData.paymentMethod},${window.receiptData.changeAmount},${window.receiptData.cashierName}\r\n`;
            }
        });

        // Create a download link and trigger it
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "receipt.csv");
        document.body.appendChild(link); // Required for FF

        link.click(); // This will download the data file named "receipt.csv"
        document.body.removeChild(link); // Clean up the link element
    });
});
