const editButtons = document.querySelectorAll('.editBtn');
let currentUserId = null;

// Extracting Years and Months form the table senctence
function extractYearsMonths(durationString) {
    // Regular expression to match "x years, y months" pattern
    const regex = /^(\d+) years, (\d+) months$/;

    // Use regex.exec to match the pattern in the string
    const match = regex.exec(durationString);

    if (match) {
        const years = parseInt(match[1]); // Extract years as integer
        const months = parseInt(match[2]); // Extract months as integer
        return {
            years,
            months
        };
    } else {
        return null; // Return null if the string doesn't match the expected pattern
    }
}

//Handling Edit click and transfering the corresponding data to edit form
editButtons.forEach(button => {
    button.addEventListener('click', function () {
        currentUserId = this.getAttribute('data-user-id-edit');
        // Find the corresponding row
        const row = button.closest('tr');
        // Get the values of the cells in the row
        const name = row.cells[0].innerText;
        const email = row.cells[1].innerText;
        const mobile = row.cells[2].innerText;
        const gender = row.cells[3].innerText;
        const totalCompanyServed = row.cells[4].innerText;
        const totalYears = extractYearsMonths(row.cells[5].innerText).years;
        const totalMonths = extractYearsMonths(row.cells[5].innerText).months;

        // Log the values to the console
        console.log('Name:', name);
        console.log('Email:', email);
        console.log('Mobile No:', mobile);
        console.log('gender:', gender);
        console.log('Total Company Served:', totalCompanyServed);
        console.log('Total Years:', totalYears);
        console.log('Total Months:', totalMonths);

        document.getElementById('Editname').value = name;
        document.getElementById('Editemail').value = email;
        document.getElementById('Editmobile').value = mobile;

        document.getElementById('Editcompany').value = parseInt(totalCompanyServed);
        document.getElementById('Edityears').value = parseInt(totalYears);
        document.getElementById('Editmonths').value = parseInt(totalMonths);
    });

});

// Function to close all Alert 
function closeAlert() {
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 3000);
}

//Function to Validate Email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Functioin to Validate Mobile Number
function validateMobile(mobile) {
    const re = /^\d{10}$/;
    return re.test(String(mobile));
}

// Function to check if value is number or not
function isNumber(value) {
    return !isNaN(value);
}

//Function to validate edit form data and update data in database 
document.getElementById('editform').addEventListener('submit', function (event) {
    // Prevent form submission
    event.preventDefault();
    console.log(currentUserId);
    // Perform custom validation here
    var name = document.getElementById('Editname').value.trim();
    var email = document.getElementById('Editemail').value.trim();
    var mobile = document.getElementById('Editmobile').value.trim();
    var gender = document.getElementById('Editgender').value.trim();
    var company = document.getElementById('Editcompany').value.trim();
    var years = document.getElementById('Edityears').value.trim();
    var months = document.getElementById('Editmonths').value.trim();



    // Check if any field is empty
    if (name === '' || email === '' || mobile === '' || gender === '' || company === '' || years === '' || months === '') {
        // Show alert message dynamically
        var alertHtml = alertElement2("Please fill all the fields");
        closeAlert()
        document.getElementById('editform').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (!validateEmail(email)) {
        var alertHtml = alertElement2("Email is invalid");
        closeAlert()
        document.getElementById('editform').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (!validateMobile(mobile)) {
        var alertHtml = alertElement2("Mobile Number is invalid");
        closeAlert()
        document.getElementById('editform').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (company < 0 || years < 0 || months < 0) {
        var alertHtml = alertElement2("Experience should never be less than 0");
        closeAlert()
        document.getElementById('editform').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (company == 0 && (years > 0 || months > 0)) {
        console.log(company, years, months)
        var alertHtml = alertElement2("if company served is 0 then years and months never be 0");
        closeAlert()
        document.getElementById('editform').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (company > 0 && (years <= 0 && months <= 0)) {
        console.log(company, years, months)
        var alertHtml = alertElement2("if company served is greater then 0 then exp should not be 0");
        closeAlert()
        document.getElementById('editform').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    }

    // If all validation passes, submit the form
    const formData = new FormData(this);
    formData.append('user_id', currentUserId);
    console.log(formData)
    if (confirm("Are you sure you want to update this data?")) {
        fetch('update_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData),
        })
            .then(response => response.text())
            .then(data => {
                alertElement2(data)
                if (data.trim() === "success") {
                    location.reload();
                }
                else {
                    alertHtml = alertElement2(data),
                        document.getElementById('editform').insertAdjacentHTML('afterbegin', alertHtml),
                        closeAlert()
                }
            })

    }
});

//Function to add data and validate data
document.getElementById('userForm').addEventListener('submit', function (event) {
    // Prevent form submission
    event.preventDefault();

    // Perform custom validation here
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var mobile = document.getElementById('mobile').value.trim();
    var gender = document.getElementById('gender').value.trim();
    var company = document.getElementById('company').value.trim();
    var years = document.getElementById('years').value.trim();
    var months = document.getElementById('months').value.trim();



    // Check if any field is empty
    if (name === '' || email === '' || mobile === '' || gender === '' || company === '' || years === '' || months === '') {
        // Show alert message dynamically
        var alertHtml = alertElement("Please fill all the fields");
        closeAlert()
        document.getElementById('main').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (!validateEmail(email)) {
        var alertHtml = alertElement("Email is invalid");
        closeAlert()
        document.getElementById('main').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (!validateMobile(mobile)) {
        var alertHtml = alertElement("Mobile Number is invalid");
        closeAlert()
        document.getElementById('main').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (company < 0 || years < 0 || months < 0) {
        var alertHtml = alertElement("Experience should never be less than 0");
        closeAlert()
        document.getElementById('main').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (company == 0 && (years > 0 || months > 0)) {
        console.log(company, years, months)
        var alertHtml = alertElement("if company served is 0 then years and months never be 0");
        closeAlert()
        document.getElementById('main').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    } else if (company > 0 && (years <= 0 || months <= 0)) {
        console.log(company, years, months)
        var alertHtml = alertElement("if company served is greater then 0 then exp should not be 0");
        closeAlert()
        document.getElementById('main').insertAdjacentHTML('afterbegin', alertHtml);
        return;
    }

    // If all validation passes, submit the form
    const formData = new FormData(this);
    fetch('add_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(formData)
    })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                location.reload();
            } else {
                console.log(data),
                    alertHtml = alertElement(data),
                    document.getElementById('main').insertAdjacentHTML('afterbegin', alertHtml),
                    closeAlert()
            }
        })

});

//Funciton to delete data in database
document.querySelectorAll(".deleteBtn").forEach(button => {
    button.addEventListener('click', function () {
        const user_id = this.getAttribute('data-user-id');
        if (confirm("Are you sure you want to delete this item?")) {
            console.log(user_id)
            fetch('delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'user_id=' + encodeURIComponent(user_id)
            })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        location.reload();
                    } else {
                        alert("Error in deleting item");
                    }
                });
        }
    })
})

//Alert element with custome message
const alertElement = (msg) => {
    return '<div class="alert alert-danger alert-dismissible fade show " role="alert" style="position: absolute;top :10px">' +
        `<strong>Error!</strong> ${msg}` +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
        '</div>'
}
const alertElement2 = (msg) => {
    return '<div class="alert alert-danger alert-dismissible fade show " role="alert" >' +
        `<strong>Error!</strong> ${msg}` +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
        '</div>'
}



