function highlightField(el) {
    el.style.backgroundColor = "#f5f5f5";
}

function resetField(el) {
    el.style.backgroundColor = "white";
}

function showError(fieldId, message) {
    const inputField = document.getElementById(fieldId);
    const errorTag = inputField.nextElementSibling;
    errorTag.textContent = message;
}

const loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const errorMessages = document.querySelectorAll("small.error-message");
        errorMessages.forEach(function (el) {
            el.textContent = "";
        });

        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const password = document.getElementById("password").value.trim();
        const agree = document.getElementById("agree").checked;

        let isValid = true;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^\d{10}$/;
        const passwordRegex = /^.{6,}$/;

        if (email === "") {
            showError("email", "Email is required");
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showError("email", "Invalid email format");
            isValid = false;
        }

        if (phone === "") {
            showError("phone", "Mobile number is required");
            isValid = false;
        } else if (!phoneRegex.test(phone)) {
            showError("phone", "Must be exactly 10 digits");
            isValid = false;
        }

        if (password === "") {
            showError("password", "Password is required");
            isValid = false;
        } else if (!passwordRegex.test(password)) {
            showError("password", "Password must be at least 6 characters");
            isValid = false;
        }

        if (!agree) {
            showError("agree", "You must agree to the Terms & Conditions");
            isValid = false;
        }

        if (isValid) {
            alert("Login successful!");
            window.location.href = "reserve.html";
        }
    });
}

function resetNotice() {
    alert("Reservation form was reset.");
}

const reserveForm = document.getElementById("reserveForm");
if (reserveForm) {
    reserveForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const guests = document.getElementById("guests").value;
        const resDate = document.getElementById("resDate").value;
        const notes = document.getElementById("notes").value;

        let selectedTime = "02:30 PM";
        if (document.getElementById("time2").checked) {
            selectedTime = "07:30 PM";
        } else if (document.getElementById("time3").checked) {
            selectedTime = "09:00 PM";
        }

        if (resDate === "") {
            alert("Please select a reservation date.");
            return;
        }

        document.getElementById("confirmText").innerHTML =
            "<strong>Table Reserved!</strong><br>" +
            "Guests: " +
            guests +
            " Persons<br>" +
            "Date: " +
            resDate +
            "<br>" +
            "Time: " +
            selectedTime +
            "<br>" +
            "Note: " +
            notes +
            "<br><br>" +
            "<a href='menu.html' class='btn'>Proceed to Food Menu</a>";
    });
}

function getQueryValue(paramName) {
    const query = window.location.search.substring(1);
    const parts = query.split("&");
    for (let i = 0; i < parts.length; i++) {
        const pair = parts[i].split("=");
        if (pair[0] === paramName) {
            return Number(pair[1]);
        }
    }
    return 0;
}

function calculateBill() {
    if (!document.getElementById("orderLines")) {
        return;
    }

    const items = [
        "Pizza",
        "Cheese Pizza",
        "Burger",
        "Shawarma",
        "Biryani",
        "Fried Rice",
    ];
    const prices = [300.0, 350.0, 450.0, 80.0, 200.0, 100.0];
    const keys = ["q1", "q2", "q3", "q4", "q5", "q6"];

    let subtotal = 0;
    let htmlOutput = "";
    let hasItems = false;

    for (let i = 0; i < items.length; i++) {
        let qty = getQueryValue(keys[i]);
        if (qty <= 0) {
            continue;
        }
        hasItems = true;
        let itemTotal = qty * prices[i];
        subtotal = subtotal + itemTotal;
        htmlOutput =
            htmlOutput +
            "<p>" +
            items[i] +
            " * " +
            qty +
            " = Rs" +
            itemTotal.toFixed(2) +
            "</p>";
    }

    if (!hasItems) {
        subtotal = 600.0;
        htmlOutput = "<p>Pizza * 2 (Default Sample Order) = Rs 600.00</p>";
    }

    if (document.getElementById("mayo").checked) {
        subtotal = subtotal + 50.0;
        htmlOutput = htmlOutput + "<p>Extra Sauce = Rs 50</p>";
    }

    if (document.getElementById("coupon").checked) {
        subtotal = subtotal - 150.0;
        if (subtotal < 0) {
            subtotal = 0;
        }
        htmlOutput = htmlOutput + "<p>Coupon Offer = - Rs 150.00</p>";
    }

    const gst = subtotal * 0.1;
    const grandTotal = subtotal + gst;

    document.getElementById("orderLines").innerHTML = htmlOutput;
    document.getElementById("subtotalText").textContent =
        "Subtotal: Rs" + subtotal.toFixed(2);
    document.getElementById("gstText").textContent =
        "GST (10%): Rs" + gst.toFixed(2);
    document.getElementById("totalText").textContent =
        "Total Payable: Rs" + grandTotal.toFixed(2);
}

function placeOrder() {
    document.getElementById("thankYouBox").style.display = "block";
    alert("Thank you! Your order has been placed.");
}
