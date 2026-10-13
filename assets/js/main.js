const audienceSelect = document.getElementById("audience");
const categorySelect = document.getElementById("category");
const booksContainer = document.getElementById("books-container");

const categories = {
    Kids: ["Infants", "Junior", "Young"],
    Adults: ["Classic Novels", "Fiction", "Comic", "Crime and Thriller"]
};

audienceSelect.addEventListener("change", function () {
    const selectedAudience = this.value;

    categorySelect.innerHTML = '<option value="">-- Select --</option>';
    booksContainer.innerHTML = "<p>Please select a category to view books.</p>";

    if (selectedAudience && categories[selectedAudience]) {
        categories[selectedAudience].forEach(category => {
            const option = document.createElement("option");
            option.value = category;
            option.textContent = category;
            categorySelect.appendChild(option);
        });
    }
});

categorySelect.addEventListener("change", function () {
    const audience = audienceSelect.value;
    const category = this.value;

    if (!audience || !category) {
        booksContainer.innerHTML = "<p>Please select a category to view books.</p>";
        return;
    }

    // Create a new AJAX request object
    const xhr = new XMLHttpRequest();

    // Open a GET request to the PHP script and pass the selected audience and category
    // encodeURIComponent() is used to safely include user-selected values in the URL
    xhr.open(
        "GET",
        `ajax/get_books.php?audience=${encodeURIComponent(audience)}&category=${encodeURIComponent(category)}`,
        true
    );

    // Define what should happen when the server sends back a response
    xhr.onload = function () {
        // If the request was successful, display the returned book data
        if (xhr.status === 200) {
            booksContainer.innerHTML = xhr.responseText;
        } else {
            // If there was a problem, show an error message instead
            booksContainer.innerHTML = "<p>Error loading books.</p>";
        }
    };

// Send the AJAX request to the server
xhr.send();
});